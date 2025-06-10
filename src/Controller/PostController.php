<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function categories(int $catId, PaginatorInterface $paginator, Request $request): Response
    {
        $categoryRepo = $this->em->getRepository(Category::class);
        $category = $categoryRepo->find($catId);

        if (!$category) {
            throw $this->createNotFoundException('Category not found.');
        }

        $postsCollection = $category->getPosts();

        $pagination = $paginator->paginate(
            $postsCollection,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('post/category-listing.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/post/{slug}', name: 'app_show_post')]
    public function show(string $slug, Request $request): Response
    {
        $repo = $this->em->getRepository(Post::class);
        $post = $repo->findOneBy(['slug' => $slug]);
        $comment = new Comment();
        $comment->setAuthor('Guest');
        $user = $this->getUser();
        if ($user) {
            $comment->setUser($user);
            $comment->setAuthor($comment->getUser()->getEmail());
        }

        $comment->setPost($post);

        $form = $this->createForm(CommentTypeForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('app_show_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
