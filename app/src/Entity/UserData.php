<?php
/**
 * UserData entity
 */
namespace App\Entity;

use App\Repository\UserDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User data
 *
 * @ORM\Entity(repositoryClass=UserDataRepository::class)
 * @ORM\Table(name="users_data")
 *
 * @UniqueEntity(fields={"nick"})
 */
class UserData
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
     * Nick
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     *
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="6",
     *     max="10",
     * )
     */
    private $nick;

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
     * Getter for Nick
     *
     * @return string|null
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Setter for Nick
     *
     * @param string $nick
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;
    }
}
