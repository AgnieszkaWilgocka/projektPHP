<?php

/**
 * Record entity
 */
namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Record
 *
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 * @ORM\Table(name="records")
 *
 * @UniqueEntity(fields={"title"})
 */
class Record
{
    /**
     * Primary key
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title
     *
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     max="50",
     * )
     *
     */
    private $title;

    /**
     * Category
     *
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="records")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * Tags
     *
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="records", orphanRemoval=true)
     * @ORM\JoinTable(name="records_tags")
     */
    private $tags;

    /**
     * Borrowings
     *
     * @ORM\OneToMany(targetEntity=Borrowing::class, mappedBy="record")
     */
    private $borrowings;

    /**
     * Amount
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Positive()
     */
    private $amount;

    /**
     * Record constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->borrowings = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for the tags.
     *
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add for tag
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * Getter for the borrowings
     *
     * @return Collection|Borrowing[]
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    /**
     * Add for borrowings
     *
     * @param Borrowing $borrowing
     *
     * @return $this
     */
    public function addBorrowing(Borrowing $borrowing): self
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings[] = $borrowing;
            $borrowing->setRecord($this);
        }

        return $this;
    }

    /**
     * Remove for borrowing
     *
     * @param Borrowing $borrowing
     *
     * @return $this
     */
    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->contains($borrowing)) {
            $this->borrowings->removeElement($borrowing);
            // set the owning side to null (unless already changed)
            if ($borrowing->getRecord() === $this) {
                $borrowing->setRecord(null);
            }
        }

        return $this;
    }

    /**
     * Getter for amount
     *
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * Setter for amount
     *
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }
}
