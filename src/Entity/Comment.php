<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[Table(name: 'comments')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $commenter = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $articles_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommenter(): ?string
    {
        return $this->commenter;
    }

    public function setCommenter(string $commenter): static
    {
        $this->commenter = $commenter;

        return $this;
    }

    public function getArticlesId(): ?string
    {
        return $this->articles_id;
    }

    public function setArticlesId(string $articles_id): static
    {
        $this->articles_id = $articles_id;

        return $this;
    }
}
