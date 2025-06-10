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
    public function __construct(private readonly EntityManagerInterface $em)
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

}
