<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{
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
}
