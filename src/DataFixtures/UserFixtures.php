<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private array $roles;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->roles = ['ROLE_USER', 'ROLE_RESTAURANT', 'ROLE_ADMIN'];
    }


    public function load(ObjectManager $manager): void
    {
        // Utilisation de faker
        $faker = Factory::create("fr_FR");

        for ($i=0; $i<20; $i++) {
            $user = new User;
            $user
                ->setPrenom($faker->lastName)
                ->setNom($faker->firstName)
                ->setEmail($faker->email)
                ->setCreatedAt($faker->dateTimeBetween('-6 month'))
                ->setActif($faker->boolean(90))
                ->setRoles([$this->roles[$faker->numberBetween(0,2)]]);

            if($faker->boolean(60)) $user->setPseudo($faker->userName);
            
            // AJOUTER MOT DE PASSE
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                "secret"
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
