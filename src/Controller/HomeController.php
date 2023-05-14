<?php

namespace App\Controller;


use App\Repository\CategorieRepository;
use App\Repository\EtablissementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private CategorieRepository $categorieRepository;
    private EtablissementRepository $etablissementRepository;

    /**
     * @param CategorieRepository $categorieRepository
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(CategorieRepository $categorieRepository, EtablissementRepository $etablissementRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->etablissementRepository = $etablissementRepository;
    }

    #[Route('/', name: 'app_home_redirection')]
    public function redirection() : Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/home', name: 'app_home')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(['accueil' => '1']),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );


        return $this->render('etablissements/index.html.twig', [
            'categories' => $this->categorieRepository->findAll(),
            'etablissements' => $etablissements,
        ]);
    }
}
