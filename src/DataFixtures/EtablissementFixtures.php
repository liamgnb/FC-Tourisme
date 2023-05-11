<?php

namespace App\DataFixtures;


use App\Entity\Etablissement;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EtablissementFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
    private VilleRepository $villeRepository;

    /**
     * @param SluggerInterface $slugger
     * @param VilleRepository $villeRepository
     */
    public function __construct(SluggerInterface $slugger, VilleRepository $villeRepository)
    {
        $this->slugger = $slugger;
        $this->villeRepository = $villeRepository;
    }

    public function load(ObjectManager $manager): void
    {
        // Utilisation de faker
        $faker = Factory::create("fr_FR");

        // Récupérations des villes depuis la BDD
        $villes = $this->villeRepository->findAll();

        // Création de 500 établissements
        for ($i=0; $i<500; $i++) {
            $etablissement = new Etablissement();
            $etablissement
                ->setNom($faker->words($faker->numberBetween(3,5), true))
                ->setSlug($this->slugger->slug($etablissement->getNom())->lower())
                ->setDescription($faker->paragraphs($faker->numberBetween(2,4), true))
                ->setTelephone($faker->e164PhoneNumber)
                ->setAdresse($faker->streetAddress)
                ->setEmail($faker->email())
                ->setActif($faker->boolean(90))
                ->setAccueil($faker->boolean(5))
                ->setCreatedAt(new \DateTime())
                ->setVille($villes[$faker->numberBetween(0,count($villes)-1)])
                ->setImage("https://fakeimg.pl/500x200/?text=".$etablissement->getNom()."&font=lobster");

            // Ajout d'une ou plusieu catégorie (max 3)
            $nbrCategorie = $faker->numberBetween(1,3);
            $historique = [];
            for ($j=0; $j<$nbrCategorie; $j++){
                $idCategorie = $faker->numberBetween(0,4);

                // Si l'id n'a pas déjà été utilisé
                if(!in_array($idCategorie, $historique)){
                    $historique[] = $idCategorie;
                    $etablissement->addCategorie($this->getReference("categorie".$idCategorie));
                }
            }
            $manager->persist($etablissement); // ordre INSERT
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class,
        ];
    }
}
