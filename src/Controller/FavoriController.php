<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param EtablissementRepository $etablissementRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EtablissementRepository $etablissementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/favoris', name: 'app_favoris')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        if($this->getUser()){
            // Récuparation des établissements actif :
            $etablissements = $this->getUser()->getEtablissements();
            $etablissementsActif  = [];
            foreach ($etablissements as $etablissement){
                if($etablissement->isActif()){
                    $etablissementsActif[] = $etablissement;
                }
            }

            // Mise en place de la pagination
            $etablissements = $paginator->paginate(
                $etablissementsActif,
                $request->query->getInt('page', 1), /*page number*/
                9 /*limit per page*/
            );

            return $this->render('favori/index.html.twig', [
                'etablissements' => $etablissements,
            ]);
        }

        return $this->redirectToRoute('app_home');

    }

    #[Route('/favoris/add/{slug}/{route}', name: 'app_favoris_add')]
    public function favoriAdd($slug, $route): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(['actif' => '1', 'slug' => $slug]);
        $user = $this->getUser();

        if($user && $etablissement && !$user->getEtablissements()->contains($etablissement)){
            $user->addEtablissement($etablissement);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } elseif ($user && $etablissement && $user->getEtablissements()->contains($etablissement)) {
            $user->removeEtablissement($etablissement);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } else {
            return $this->redirectToRoute('app_home');
        }

        switch ($route) {
            case 'app_etablissements_slug':
                return $this->render('etablissements/detail.html.twig', [
                    'etablissement' => $etablissement,
                ]);

            case 'app_favoris':
                return $this->redirectToRoute('app_favoris');

            default :
                return $this->redirect('/'.$route);
        }

    }
}
