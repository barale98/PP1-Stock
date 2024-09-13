<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewStockController extends AbstractController
{
    #[Route('/view-stock', name: 'app_view_stock')]
    public function index(): Response
    {
        // Aquí puedes agregar lógica para obtener datos del stock si es necesario
        return $this->render('view_stock/view_stock.html.twig', [
            'controller_name' => 'ViewStockController',
        ]);
    }
}