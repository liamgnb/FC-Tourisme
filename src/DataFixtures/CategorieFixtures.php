<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $categories = ['restaurant', 'hôtel', 'gîte', 'musée', 'artisanat'];

        foreach ($categories as $key => $categorie) {
            $newCategorie = new Categorie();
            $newCategorie->setNom($categorie)->setCreatedAt(new \DateTime());
            $this->addReference("categorie".$key, $newCategorie);
            $manager->persist($newCategorie); // ordre INSERT
        }

        $manager->flush();
    }
}
