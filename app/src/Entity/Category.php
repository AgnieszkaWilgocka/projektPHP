<?php
/**
 *  Category entity.
 */
namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 *
 * @UniqueEntity(fields={"name"})
 */
class Category
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45
     * )
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     max="50",
     * )
     */
    private $name;

    /**
     * Records
     *
     * @ORM\OneToMany(targetEntity=Record::class, mappedBy="category")
     *
     */
    private $records;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->records = new ArrayCollection();
    }

    /**Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     *
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for the records
     *
     * @return Collection|Record[]
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    /**
     * Add for record
     *
     * @param Record $record
     *
     * @return $this
     */
    public function addRecord(Record $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records[] = $record;
            $record->setCategory($this);
        }

        return $this;
    }

    /**
     * Remove for record
     *
     * @param Record $record
     *
     * @return $this
     */
    public function removeRecord(Record $record): self
    {
        if ($this->records->contains($record)) {
            $this->records->removeElement($record);
            // set the owning side to null (unless already changed)
            if ($record->getCategory() === $this) {
                $record->setCategory(null);
            }
        }

        return $this;
    }
}
