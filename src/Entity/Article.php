<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Table(name: 'articles')]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $article_title = null;

    #[ORM\Column(length: 255)]
    private ?string $article_url = null;

    #[ORM\Column(length: 255)]
    private ?string $img_original = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intro = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $published = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $on_first_page = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $has_full_content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $article_description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $article_keywords = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?string $categories_id = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(name: 'categories_id', referencedColumnName: 'id')]
    private Category|null $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    #[ORM\JoinTable(name: 'tag_article')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleTitle(): ?string
    {
        return $this->article_title;
    }

    public function setArticleTitle(string $article_title): static
    {
        $this->article_title = $article_title;

        return $this;
    }

    public function getArticleUrl(): ?string
    {
        return $this->article_url;
    }

    public function setArticleUrl(string $article_url): static
    {
        $this->article_url = $article_url;

        return $this;
    }

    public function getImgOriginal(): ?string
    {
        return $this->img_original;
    }

    public function setImgOriginal(?string $img_original): static
    {
        $this->img_original = $img_original;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(?string $intro): static
    {
        $this->intro = $intro;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(int $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getOnFirstPage(): ?bool
    {
        return $this->on_first_page;
    }

    public function setOnFirstPage(int $on_first_page): static
    {
        $this->on_first_page = $on_first_page;

        return $this;
    }

    public function getHasFullContent(): ?bool
    {
        return $this->has_full_content;
    }

    public function setHasFullContent(int $has_full_content): static
    {
        $this->has_full_content = $has_full_content;

        return $this;
    }

    public function getArticleDescription(): ?string
    {
        return $this->article_description;
    }

    public function setArticleDescription(?string $article_description): static
    {
        $this->article_description = $article_description;

        return $this;
    }

    public function getArticleKeywords(): ?string
    {
        return $this->article_keywords;
    }

    public function setArticleKeywords(string $article_keywords): static
    {
        $this->article_keywords = $article_keywords;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCategoriesId(): ?string
    {
        return $this->categories_id;
    }

    public function setCategoriesId(?string $categories_id): static
    {
        $this->categories_id = $categories_id;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(ArrayCollection $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addArticle($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeArticle($this);
        }

        return $this;
    }
}
