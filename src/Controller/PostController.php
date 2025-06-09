<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        $repo = $this->em->getRepository(Post::class);
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $repo->findAll(),
        ]);
    }

    #[Route('/post/category/{catId}', name: 'app_show_category')]
    public function categories(int $catId): Response
    {
        $categoryRepo = $this->em->getRepository(Category::class);
        $category = $categoryRepo->find($catId);

        if (!$category) {
            throw $this->createNotFoundException('Category not found.');
        }

        return $this->render('post/index.html.twig', [
            'category' => $category,
            'posts' => $category->getPosts(),
        ]);
    }

    #[Route('/post/{slug}', name: 'app_show_post')]
    public function show(string $slug): Response
    {
        $repo = $this->em->getRepository(Post::class);

        return $this->render('post/post.html.twig', [
            'controller_name' => 'PostController',
            'post' => $repo->findOneBy(['slug' => $slug]),
        ]);
    }
}
