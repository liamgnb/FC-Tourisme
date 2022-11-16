<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtablissementsController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }

    #[Route('/etablissements', name: 'app_etablissements')]
    public function index(PaginatorInterface $paginator, Request $request): Response
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
}
