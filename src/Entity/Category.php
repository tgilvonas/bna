<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $active = null;

    #[ORM\Column(length: 255)]
    private ?string $category_name = null;

    #[ORM\Column(length: 255)]
    private ?string $category_url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $category_description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $category_keywords = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $ord = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Article::class)]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(int $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): static
    {
        $this->category_name = $category_name;

        return $this;
    }

    public function getCategoryUrl(): ?string
    {
        return $this->category_url;
    }

    public function setCategoryUrl(string $category_url): static
    {
        $this->category_url = $category_url;

        return $this;
    }

    public function getCategoryDescription(): ?string
    {
        return $this->category_description;
    }

    public function setCategoryDescription(?string $category_description): static
    {
        $this->category_description = $category_description;

        return $this;
    }

    public function getCategoryKeywords(): ?string
    {
        return $this->category_keywords;
    }

    public function setCategoryKeywords(?string $category_keywords): static
    {
        $this->category_keywords = $category_keywords;

        return $this;
    }

    public function getOrd(): ?int
    {
        return $this->ord;
    }

    public function setOrd(int $ord): static
    {
        $this->ord = $ord;

        return $this;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function setArticles($articles): self
    {
        $this->articles = $articles;

        return $this;
    }
}
