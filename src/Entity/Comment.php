<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comment")
 * @ApiResource(
 *     normalizationContext={"groups"={"comments_read"}}
 * )
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("comments_read")
	 */
    private $id;

    /**
     * @ORM\Column(type="datetime")
	 * @Groups("comments_read")
	 */
    private $publishedAt;

    /**
     * @ORM\Column(type="text")
	 * @Groups("comments_read")
	 */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Quote::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quote;

    public function __construct()
	{
		$this->publishedAt = new \DateTime();
	}

	public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): self
    {
        $this->quote = $quote;

        return $this;
    }
}
