<?php

namespace App\Controller;

use App\Entity\Maquinaria;
use App\Repository\MaquinariaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/maquinaria')]
class MaquinariaController extends AbstractController
{
    #[Route('/', name: 'maquinaria_index', methods: ['GET'])]
    public function index(MaquinariaRepository $maquinariaRepository): Response
    {
        // Obtener todas las maquinarias de la base de datos
        $maquinarias = $maquinariaRepository->findAll();

        // Pasar la variable 'maquinarias' a la vista
        return $this->render('Maquinarias/maquinaria.html.twig', [
            'maquinarias' => $maquinarias,
        ]);
    }

    #[Route('/new', name: 'maquinaria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MaquinariaRepository $maquinariaRepository, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
        // Procesar el formulario
        $maquinaria = new Maquinaria();
        $maquinaria->setNombre($request->request->get('nombre'));
        $maquinaria->setMarca($request->request->get('marca'));
        $maquinaria->setDescripcion($request->request->get('descripcion'));
        $maquinaria->setCantidad((int) $request->request->get('cantidad'));
        $maquinaria->setAñosUso((int) $request->request->get('años_uso'));

        $ultimoService = $request->request->get('ultimo_service');
        if ($ultimoService) {
            $maquinaria->setUltimoService(new \DateTime($ultimoService));
        }

        $imagen = $request->files->get('imagen');
        if ($imagen) {
            $imagenFilename = uniqid() . '.' . $imagen->guessExtension();
            $imagen->move($this->getParameter('images_directory'), $imagenFilename);
            $maquinaria->setImagen($imagenFilename);
        }

        $entityManager->persist($maquinaria);
        $entityManager->flush();

        $this->addFlash('success', 'Maquinaria agregada con éxito.');
        return $this->redirectToRoute('maquinaria_index');
    }

    // Obtener todas las maquinarias para pasarlas a la vista
    $maquinarias = $maquinariaRepository->findAll();

    // Renderizar la plantilla y pasar la variable maquinarias
    return $this->render('Maquinarias/Maquinaria.html.twig', [
        'maquinarias' => $maquinarias,
    ]);
    }


    #[Route('/{id}/delete', name: 'maquinaria_delete', methods: ['POST'])]
    public function delete(Request $request, Maquinaria $maquinaria, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maquinaria->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maquinaria);
            $entityManager->flush();

            $this->addFlash('success', 'La maquinaria ha sido eliminada con éxito.');
        }

        return $this->redirectToRoute('app_view_catalog');
    }

    #[Route('/visualizar-stock', name: 'visualizar_stock', methods: ['GET'])]
    public function visualizarStock(MaquinariaRepository $maquinariaRepository): Response
    {
        $maquinarias = $maquinariaRepository->findAll();
        return $this->render('maquinaria/visualizar_stock.html.twig', [
            'maquinarias' => $maquinarias,
            'controller_name' => 'MaquinariaController',
        ]);
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