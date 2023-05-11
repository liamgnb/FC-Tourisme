<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieFixtures extends Fixture
{
    private SluggerInterface $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
       $categories = ['restaurant', 'hôtel', 'gîte', 'musée', 'artisanat'];

        foreach ($categories as $key => $categorie) {
            $newCategorie = new Categorie();
            $newCategorie->setNom($categorie)
                ->setCreatedAt(new \DateTime())
                ->setSlug($this->slugger->slug($newCategorie->getNom())->lower());
            $this->addReference("categorie".$key, $newCategorie);
            $manager->persist($newCategorie); // ordre INSERT
        }

        $manager->flush();
    }
}
