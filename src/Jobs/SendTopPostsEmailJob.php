<?php

namespace App\Jobs;

use App\Mail\SendTopPostsEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class SendTopPostsEmailJob
{
    public function __construct(
        private EntityManagerInterface $em,
        private  MailerInterface $mailer,
    ) {}

    public function __invoke(SendTopPostsEmail $message): void
    {
        $posts = $this->em->createQuery(
            'SELECT p FROM App\Entity\Post p ORDER BY SIZE(p.comments) DESC'
        )->setMaxResults(10)->getResult();


        $content = "Top 10 Posts:\n";
        foreach ($posts as $post) {
            $content .= sprintf("- %s (%d comments)\n", $post->getTitle(), count($post->getComments()));
        }

        $email = (new Email())
            ->from('noreply@example.com')
            ->to('admin@example.com')
            ->subject('Weekly Top 10 Posts')
            ->text($content);

        $this->mailer->send($email);
    }
}
