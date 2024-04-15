<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
 #[ORM\Entity(repositoryClass: UserRepository::class)]
 #[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
 #[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    
     

    private $updatedAt;

    
    public function __construct()
    {    
        $this->status = 0; // Set default value to 0
        $this->roles = 'ROLE_USER';
       
       
       
    }




     #[ORM\Column]
     #[ORM\Id]
     #[ORM\GeneratedValue]
    private ?int $id = null;

    
    #[ORM\Column(type:"string", length:500, unique:true)]
     #[Assert\NotBlank(message: 'name is required')]
     private ?string $name = null;


    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Email {{ value }} must be valid')]
     private ?string $email = null;


    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: 'phone  is required')]
    private ?string $tel = null;

     /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: 'password is required')]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;


    #[ORM\Column(length: 500)]
    private ?string $roles = null;


    #[ORM\Column(length: 500)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column]
   public ?int $status = null;



   #[ORM\Column(length: 500)]
    private ?string $resetToken = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        if ($password !== null) {
            $this->password = $password;
        }
    
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->roles;
    }

    public function setRole(string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
{
    // Check if $imageName is not null before setting it
    if ($imageName !== null) {
        $this->imageName = $imageName;
    }

    return $this;
}
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) 
        {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    public function getStatus(): ?int
{
    return $this->status;
}

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
   

     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

/**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
{
    // Check if $roles is already an array, otherwise convert it
    if (is_array($this->roles)) {
        $roles = $this->roles;
    } else {
        // If $roles is a string, explode it into an array using a delimiter
        $roles = explode(',', $this->roles);
    }
    
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';
    
    // Remove any duplicates and return the unique roles
    return array_unique($roles);
}

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }


    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }


    public function getUser(): object
    {
        return $this->user;
    }

}
