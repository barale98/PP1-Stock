<?php

namespace App\Controller;

use App\Entity\Maquinaria;
use App\Repository\MaquinariaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Asegúrate de importar esta clase
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/maquinaria')]
class MaquinariaController extends AbstractController
{
    #[Route('/', name: 'maquinaria_index', methods: ['GET'])]
    public function index(MaquinariaRepository $maquinariaRepository): Response
    {
        return $this->render('Maquinarias/maquinaria.html.twig', [
            'maquinarias' => $maquinariaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'maquinaria_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $maquinaria = new Maquinaria();
        $maquinaria->setCantidad((int) $request->request->get('cantidad'));

        $entityManager->persist($maquinaria);
        $entityManager->flush();

        return $this->redirectToRoute('maquinaria_index');
    }

    #[Route('/{id}/delete', name: 'maquinaria_delete', methods: ['POST'])]
    public function delete(Request $request, Maquinaria $maquinaria, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maquinaria->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maquinaria);
            $entityManager->flush();
        }

        return $this->redirectToRoute('maquinaria_index');
    }

    #[Route('/maquinaria/{id}/edit', name: 'maquinaria_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Maquinaria $maquinaria, EntityManagerInterface $entityManager): Response
{
    if ($request->isMethod('POST')) {
        $cantidad = $request->request->get('cantidad');
        $maquinaria->setCantidad((int) $cantidad);
        $entityManager->flush();

        return $this->redirectToRoute('maquinaria_index');
    }

    return $this->render('Maquinarias/edit.html.twig', [
        'maquinaria' => $maquinaria,
    ]);
}

}
