<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\AnimalCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use function Symfony\Component\String\u;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUser($manager);
        $this->loadAnimalCategory($manager);
        $this->loadAnimal($manager);
    }

    private function loadUser(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$firstName, $secondName, $email, $password, $roles]) {
            $user = new User();
            $user->setFirstName($firstName);
            $user->setSecondName($secondName);
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($email, $user);
        }

        $manager->flush();
    }

    private function loadAnimalCategory(ObjectManager $manager): void
    {
        foreach ($this->getAnimalCategoryData() as $title) {
            $animalCategory = new AnimalCategory();
            $animalCategory->setTitle($title);
            $animalCategory->setSlug($this->slugger->slug($title));

            $manager->persist($animalCategory);
            $this->addReference('category-'.$title, $animalCategory);
        }

        $manager->flush();
    }

    private function loadAnimal(ObjectManager $manager): void
    {
        foreach ($this->getAnimalData() as [$user, $title, $description, $image, $publication_date, $category]) {
            $animal = new Animal();
            $animal->setUser($user);
            $animal->setTitle($title);
            $animal->setDescription($description);
            $animal->setImage($image);
            $animal->setPublicationDate($publication_date);
            $animal->setCategory($category);

            $manager->persist($animal);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['Jane', 'Smith', 'jane_smith@test.com', 'qwerty', [User::ROLE_USER]],
            ['Tom', 'Smith', 'tom_smith@test.com', 'qwerty', [User::ROLE_USER]],
            ['John', 'Smith', 'john_smith@test.com', 'qwerty', [User::ROLE_USER]],
        ];
    }

    private function getAnimalCategoryData(): array
    {
        return ['Dog', 'Puppy', 'Cat', 'Kitten'];
    }

    private function getAnimalData(): array
    {
        $animals = [];

        foreach ($this->getPhrases() as $i => $title) {
            $user = $this->getReference(['jane_smith@test.com', 'tom_smith@test.com', 'john_smith@test.com'][random_int(0, 2)]);

            $animals[] = [
                $user,
                $title,
                $this->getRandomText(),
                $this->getRandomImage()[random_int(0, 1)],
                new \DateTimeImmutable('now - '.$i.'days'),
                $this->getReference('category-'.$this->getAnimalCategoryData()[random_int(0, 3)]),
            ];
        }

        return $animals;
    }

    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getRandomText(int $maxLength = 255): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        do {
            $text = u('. ')->join($phrases)->append('.');
            array_pop($phrases);
        } while ($text->length() > $maxLength);

        return $text;
    }

    private function getRandomImage(): array
    {
        return ['first_test_image.png', 'second_test_image.png'];
    }
}
