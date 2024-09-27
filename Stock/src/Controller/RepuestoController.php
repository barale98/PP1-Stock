<?php

namespace App\Controller;

use App\Entity\Repuestos;
use App\Repository\RepuestosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/repuestos')]
class RepuestoController extends AbstractController
{
    #[Route('/', name: 'repuestos_index', methods: ['GET'])]
    public function index(RepuestosRepository $repuestosRepository): Response
    {
        return $this->render('repuestos/add_repuestos.html.twig', [
            'repuestos' => $repuestosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'repuestos_new', methods: ['GET'])]
    public function new(): Response
    {
        return $this->render('repuestos/add_repuestos.html.twig');  
    }

    #[Route('/create', name: 'repuestos_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nombre = $request->request->get('nombre');
        $descripcion = $request->request->get('descripcion');
        $cantidad = $request->request->get('cantidad');

        // Validación de campos
        if (empty($nombre) || empty($descripcion) || !is_numeric($cantidad) || $cantidad < 0) {
            $this->addFlash('error', 'Todos los campos son obligatorios y la cantidad debe ser un número no negativo.');
            return $this->redirectToRoute('repuestos_new');
        }

        // Crear y guardar el nuevo repuesto
        $repuesto = new Repuestos();
    $repuesto->setNombre($request->request->get('nombre'));
    $repuesto->setDescripcion($request->request->get('descripcion'));
    $repuesto->setCantidad((int) $request->request->get('cantidad'));
    $repuesto->setStockMinimo((int) $request->request->get('stock_minimo'));  // Asegúrate de obtener este valor desde el formulario


        $entityManager->persist($repuesto);
        $entityManager->flush();

        // Agregar el mensaje flash de éxito
        $this->addFlash('success', 'Repuesto agregado correctamente.');

        // Redirigir a la página principal de repuestos
        return $this->redirectToRoute('repuestos_index');
    }

    #[Route('/{id<\d+>}', name: 'repuestos_show', methods: ['GET'])]
    public function show(RepuestosRepository $repuestosRepository, int $id): Response
    {
        $repuesto = $repuestosRepository->find($id);

        if (!$repuesto) {
            throw $this->createNotFoundException('Repuesto no encontrado.');
        }

        return $this->render('repuestos/show.html.twig', [
            'repuesto' => $repuesto,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'repuestos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repuestos $repuesto, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nombre = $request->request->get('nombre');
            $descripcion = $request->request->get('descripcion');
            $cantidad = (int)$request->request->get('cantidad');

            $repuesto->setNombre($nombre);
            $repuesto->setDescripcion($descripcion);
            $repuesto->setCantidad($cantidad);

            $entityManager->flush();

            return $this->redirectToRoute('repuestos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repuestos/edit.html.twig', [
            'repuesto' => $repuesto,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'repuestos_delete', methods: ['POST'])]
    public function delete(Request $request, Repuestos $repuesto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repuesto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repuesto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('repuestos_index', [], Response::HTTP_SEE_OTHER);
    }
}