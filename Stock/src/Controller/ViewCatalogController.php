<?php

namespace App\Controller;

use App\Entity\Maquinaria;
use App\Repository\MaquinariaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/maquinaria/edit/{id}', name: 'maquinaria_edit', methods: ['POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Maquinaria $maquinaria): Response
    {
        $maquinaria->setNombre($request->request->get('nombre'));
        $maquinaria->setMarca($request->request->get('marca'));
        $maquinaria->setCantidad((int) $request->request->get('cantidad'));
        $maquinaria->setDescripcion($request->request->get('descripcion'));

        $entityManager->flush();

        return $this->redirectToRoute('app_view_catalog');
    }

    #[Route('/maquinaria/delete/{id}', name: 'maquinaria_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, Maquinaria $maquinaria): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maquinaria->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maquinaria);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_view_catalog');
    }
}