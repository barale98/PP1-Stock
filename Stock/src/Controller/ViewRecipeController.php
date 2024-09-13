<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewRecipeController extends AbstractController
{
    #[Route('/view-recipe', name: 'app_view_recipe')]
    public function index(): Response
    {
        // Aquí puedes agregar lógica para obtener datos de las recetas si es necesario
        return $this->render('view_recipe/view_recipe.html.twig', [
            'controller_name' => 'ViewRecipeController',
        ]);
    }
}