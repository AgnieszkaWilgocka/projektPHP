<?php

/**
 * User entity
 */
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="email_idx",
 *                  columns={"email"},
 *              )
 *          }
 *      )
 */
class User implements UserInterface
{
    /**
     * Role user.
     *
     * @var string
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * Role admin.
     *
     * @var string
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Primary key
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Email
     *
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email."
     * )
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $email;

    /**
     * Roles
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * Password
     *
     * @var string The hashed password
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="6",
     *     max="20",
     * )
     * @Assert\NotBlank()
     *
     */
    private $password;

    /**
     * User data
     *
     * @ORM\OneToOne(targetEntity=UserData::class, cascade={"persist", "remove"})
     */
    private $userData;




    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for E-mail.
     *
     * @return string|null E-mail
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for E-mail.
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for user name
     *
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string $email E-mail
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for the Roles.
     *
     * @see UserInterface
     *
     * @return array Roles
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Setter for the Roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for Password.
     *
     * @see UserInterface
     *
     * @return string Password
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Setter for Password.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Getter for User data
     *
     * @return UserData|null
     */
    public function getUserData(): ?UserData
    {
        return $this->userData;
    }

    /**
     * Setter for User data
     *
     * @param UserData|null $userData
     */
    public function setUserData(?UserData $userData): void
    {
        $this->userData = $userData;
    }
}
