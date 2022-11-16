<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        $etablissements = $this->etablissementRepository->findBy(['actif' => '1']);


        return $this->render('etablissements/index.html.twig');
    }
}
