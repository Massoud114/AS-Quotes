<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuoteRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=QuoteRepository::class)
 * @ORM\Table(name="asquotes_quote")
 * @ApiResource(
 *     normalizationContext={"groups"={"quotes_read"}}
 * )
 */
class Quote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
	 * @Groups("quotes_read")
	 */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
	 * @Groups("quotes_read")
	 */
    private $publishedAt;

    /**
     * @ORM\Column(type="integer")
	 * @Groups("quotes_read")
	 */
    private $nb_views;

    /**
     * @ORM\Column(type="integer")
	 * @Groups("quotes_read")
	 */
    private $nbDownloads;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allowed;

    /**
     * @ORM\Column(type="integer")
	 * @Groups("quotes_read")
	 */
    private $nbLikes;

    /**
     * @ORM\Column(type="text", nullable=true)
	 * @Groups("quotes_read")
	 */
    private $description;

	/**
	 * @var Comment[]|ArrayCollection
	 *
	 * @ORM\OneToMany(
	 *      targetEntity=Comment::class,
	 *      mappedBy="quote",
	 *      orphanRemoval=true,
	 *      cascade={"persist"}
	 * )
	 * @ORM\OrderBy({"publishedAt": "DESC"})
	 * @Groups("quotes_read")
	 */
	private $comments;

	/**
	 * @var Tag[]|ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"})
	 * @ORM\JoinTable(name="asquotes_tag")
	 * @ORM\OrderBy({"name": "ASC"})
	 * @Groups("quotes_read")
	 */
	private $tags;

    /**
	 * @ORM\OneToOne(targetEntity=MediaObject::class, cascade={"persist", "remove"})
	 * @ApiProperty(iri="http://localhost:8000/image")
	 * @Groups("quotes_read")
	 */
    private $image;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->createdAt = new DateTime('now');
		$this->nbDownloads = 0;
		$this->nbLikes = 0;
		$this->nb_views = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getNbViews(): ?int
    {
        return $this->nb_views;
    }

    public function setNbViews(int $nb_views): self
    {
        $this->nb_views = $nb_views;

        return $this;
    }

    public function getNbDownloads(): ?int
    {
        return $this->nbDownloads;
    }

    public function setNbDownloads(int $nbDownloads): self
    {
        $this->nbDownloads = $nbDownloads;

        return $this;
    }

    public function getAllowed(): ?bool
    {
        return $this->allowed;
    }

    public function setAllowed(bool $allowed): self
    {
        $this->allowed = $allowed;

        return $this;
    }

    public function getNbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setNbLikes(int $nbLikes): self
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setQuote($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getQuote() === $this) {
                $comment->setQuote(null);
            }
        }

        return $this;
    }

	public function addTag(ArrayCollection $tags): void
	{
		foreach ($tags as $tag) {
			if (!$this->tags->contains($tag)) {
				$this->tags->add($tag);
			}
		}
	}

	public function removeTag(Tag $tag): void
                  	{
                  		$this->tags->removeElement($tag);
                  	}

	public function getTags(): Collection
                  	{
                  		return $this->tags;
                  	}

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;

        return $this;
    }
}
