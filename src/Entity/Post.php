<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post', cascade: ['persist'], orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    #[ORM\JoinTable(name: 'post_category')]
    private Collection $categories;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug(): void
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->title);
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getComments(): Collection { return $this->comments; }
    public function getCategories(): Collection { return $this->categories; }

    public function setCategories(Collection $categories): static
    {
        $this->categories = $categories;
        return $this;
    }


    public function setComments(Collection $comments): static
    {
        $this->comments = $comments;
        return $this;
    }
}
