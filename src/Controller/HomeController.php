<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $categoryRepository = $this->em->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        $categoryIds = array_map(fn(Category $c) => $c->getId(), $categories);

        $postRepository = $this->em->getRepository(Post::class);
        $latestPostsByCategory = $postRepository->findLatestPostsByCategories($categoryIds);

        return $this->render('home/index.html.twig', [
            'posts' => $latestPostsByCategory,
            'categories' => $categories,
        ]);
    }

    // src/Repository/PostRepository.php

    public function findLatestPostsByCategories(array $categoryIds, int $limitPerCategory = 3): array
    {
        $conn = $this->em->getConnection();

        $sql = '
        SELECT p.*, pc.category_id
        FROM post p
        INNER JOIN post_category pc ON p.id = pc.post_id
        WHERE pc.category_id IN (:categoryIds)
        ORDER BY pc.category_id, p.created_at DESC
    ';

        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['categoryIds' => $categoryIds]);

        $rows = $stmt->fetchAllAssociative();
        
        $postsByCategory = [];

        foreach ($rows as $row) {
            $catId = $row['category_id'];
            if (!isset($postsByCategory[$catId])) {
                $postsByCategory[$catId] = [];
            }
            if (count($postsByCategory[$catId]) < $limitPerCategory) {
                $postsByCategory[$catId][] = $row;
            }
        }

        return $postsByCategory;
    }

}
