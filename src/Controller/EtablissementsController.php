<?php

namespace App\Controller;

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

    /**
     * @param EtablissementRepository $etablissementRepository
     * @param UserRepository $userRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository, UserRepository $userRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
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
        ]);
    }

    #[Route('/etablissements/{slug}', name: 'app_etablissements_slug')]
    public function detail($slug): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(['actif' => '1', 'slug' => $slug]);

        return $this->render('etablissements/detail.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

    #[Route('/etablissements/favori/add/{slug}', name: 'app_etablissements_favori_add')]
    public function favoriAdd($slug): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(['actif' => '1', 'slug' => $slug]);
        $user = $this->getUser();

        if($user && !$user->getEtablissements()->contains($etablissement)){
            $user->addEtablissement($etablissement);
            $this->userRepository->save($user, true);
        } elseif ($user && $user->getEtablissements()->contains($etablissement)) {
            $user->removeEtablissement($etablissement);
            $this->userRepository->save($user, true);
        }

        return $this->render('etablissements/detail.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

}
