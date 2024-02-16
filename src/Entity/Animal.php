<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[Assert\Image(
        maxSize: '1024K',
        maxSizeMessage: 'Файл слишком большой!',
        mimeTypesMessage: 'Неверный формат файла!'
    )]
    private File $uploadedImage;

    #[ORM\Column(type: 'string', length: 255)]
    private string $image;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $publicationDate;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private UserInterface $user;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: AnimalCategory::class)]
    private AnimalCategory $category;

    public function __construct()
    {
        $this->publicationDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPublicationDate(): \DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): AnimalCategory
    {
        return $this->category;
    }

    public function setCategory(AnimalCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUploadedImage(): File
    {
        return $this->uploadedImage;
    }

    public function setUploadedImage(File $uploadedImage): static
    {
        $this->uploadedImage = $uploadedImage;

        return $this;
    }
}
