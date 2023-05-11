<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtablissementsController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;
    private UserRepository $userRepository;
    private CategorieRepository $categorieRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     * @param UserRepository $userRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository, UserRepository $userRepository, CategorieRepository $categorieRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/etablissements', name: 'app_etablissements')]
    public function all(PaginatorInterface $paginator, Request $request): Response
    {
        // Mise en place de la pagination
        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(['actif' => '1'], ['nom' => 'ASC']),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('etablissements/index.html.twig', [
            'etablissements' => $etablissements,
            'categories' => $this->categorieRepository->findAll(),
        ]);
    }

    #[Route('/etablissements/{slug}', name: 'app_etablissements_slug')]
    public function detail($slug): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(['actif' => '1', 'slug' => $slug]);

        return $this->render('etablissements/detail.html.twig', [
            'etablissement' => $etablissement,
            'categories' => $this->categorieRepository->findAll(),
        ]);
    }

    #[Route('/categories/{slug}', name: 'app_categorie_slug')]
    public function categorie($slug, PaginatorInterface $paginator, Request $request): Response
    {
        $categorie = $this->categorieRepository->findOneBy(['slug' => $slug]);
        $etablissementsCategorie = $categorie->getEtablissements();

        $etablissementsActif = [];

        foreach ($etablissementsCategorie as $etablissement){
            if ($etablissement->isActif()) $etablissementsActif[] = $etablissement;
        }

        // Mise en place de la pagination
        $etablissements = $paginator->paginate(
            $etablissementsActif,
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('etablissements/index.html.twig', [
            'etablissements' => $etablissements,
            'categories' => $this->categorieRepository->findAll(),
        ]);
    }

}
