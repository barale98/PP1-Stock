<?php

namespace App\Controller;

use App\Entity\Repuestos;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewStockController extends AbstractController
{
    #[Route('/view-stock', name: 'app_view_stock')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repuestos = $entityManager->getRepository(Repuestos::class)->findAll();

        return $this->render('view_stock/view_stock.html.twig', [
            'repuestos' => $repuestos,
        ]);
    }

    #[Route('/repuestos/new', name: 'repuestos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repuesto = new Repuestos();
        $repuesto->setNombre($request->request->get('nombre'));
        $repuesto->setCantidad((int) $request->request->get('cantidad'));
        $repuesto->setDescripcion($request->request->get('descripcion'));

        $entityManager->persist($repuesto);
        $entityManager->flush();

        return $this->redirectToRoute('app_view_stock');
    }

    #[Route('/repuestos/edit/{id}', name: 'repuestos_edit', methods: ['POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Repuestos $repuesto): Response
    {
        $repuesto->setNombre($request->request->get('nombre'));
        $repuesto->setCantidad((int) $request->request->get('cantidad'));
        $repuesto->setDescripcion($request->request->get('descripcion'));

        $entityManager->flush();

        return $this->redirectToRoute('app_view_stock');
    }

    #[Route('/repuestos/delete/{id}', name: 'repuestos_delete', methods: ['POST', 'DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Repuestos $repuesto): Response
    {
        $entityManager->remove($repuesto);
        $entityManager->flush();

        return $this->redirectToRoute('app_view_stock');
    }
}