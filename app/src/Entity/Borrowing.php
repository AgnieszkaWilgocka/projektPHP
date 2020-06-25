<?php

/**
 * Borrowing entity
 */
namespace App\Entity;

use App\Repository\BorrowingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BorrowingRepository::class)
 * @ORM\Table(name="borrowings")
 */
class Borrowing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Record::class, inversedBy="borrowings")
     */
    private $record;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="1",
     *     max="50",
     * )
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     */
    private $isExecuted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;


    /**
     * Getter for Id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Record
     *
     * @return Record|null
     */
    public function getRecord(): ?Record
    {
        return $this->record;
    }

    /**
     * Setter for Record
     *
     * @param Record|null $record
     */
    public function setRecord(?Record $record): void
    {
        $this->record = $record;
    }

    /**
     * Getter for CreatedAt
     *
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for CreatedAt
     *
     * @param \DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for Comment
     *
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Setter for Comment
     *
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Getter for IsExecuted
     *
     * @return bool|null
     */
    public function getIsExecuted(): ?bool
    {
        return $this->isExecuted;
    }

    /**
     * Setter for IsExecuted
     *
     * @param bool|null $isExecuted
     */
    public function setIsExecuted(?bool $isExecuted): void
    {
        $this->isExecuted = $isExecuted;
    }

    /**
     * Getter for Author
     *
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for Author
     *
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }


}
