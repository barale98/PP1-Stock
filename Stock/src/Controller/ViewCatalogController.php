<?php

namespace App\Controller;

use App\Repository\MaquinariaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewCatalogController extends AbstractController
{
    #[Route('/view-catalog', name: 'app_view_catalog', methods: ['GET'])]
    public function viewCatalog(MaquinariaRepository $maquinariaRepository): Response
    {
        $maquinarias = $maquinariaRepository->findAll();
        return $this->render('view_catalog/view_catalog.html.twig', [
            'maquinarias' => $maquinarias,
            'controller_name' => 'MaquinariaController',
        ]);
    }
}