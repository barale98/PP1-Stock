<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/home-page', name: 'home_page')]  // Añadimos esta línea
    public function index(): Response
    {
        return $this->render('home_page/home_page.html.twig');
    }

    /**
     * @Route("/visualizar-stock", name="visualizar_stock")
     */
    public function visualizarStock(): Response
    {
        // Lógica para visualizar stock
        return $this->render('view_stock/view_stock.html.twig');
    }

    /**
     * @Route("/visualizar-catalogo", name="visualizar_catalogo")
     */
    public function visualizarCatalogo(): Response
    {
        // Lógica para visualizar catálogo
        return $this->render('view_catalog/view_catalog.html.twig');
    }
    /**
     * @Route("/cerrar-sesion", name="cerrar_sesion")
     */
    public function cerrarSesion(): Response
    {
        // Lógica para cerrar sesión
        // ...

        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/agregar-maquinaria", name="agregar_maquinaria")
     */
    public function agregarMaquinaria(): Response
    {
        return $this->render('Maquinarias/Maquinaria.html.twig');
    }
}