<?php

namespace App\Controller;

use App\Entity\Repuestos;
use App\Repository\RepuestosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    #[Route('/new', name: 'repuestos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $repuesto = new Repuestos();
            $repuesto->setNombre($request->request->get('nombre'));
            $repuesto->setDescripcion($request->request->get('descripcion'));
            $repuesto->setCantidad((int) $request->request->get('cantidad'));

            $entityManager->persist($repuesto);
            $entityManager->flush();

            return $this->redirectToRoute('repuestos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repuestos/add_repuestos.html.twig');
    }

    #[Route('/{id}', name: 'repuestos_show', methods: ['GET'])]
    public function show(Repuestos $repuesto): Response
    {
        return $this->render('repuestos/show.html.twig', [
            'repuesto' => $repuesto,
        ]);
    }

    #[Route('/{id}/edit', name: 'repuestos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repuestos $repuesto, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nombre = $request->request->get('nombre');
            $descripcion = $request->request->get('descripcion');
            $cantidad = $request->request->get('cantidad');

            $repuesto->setNombre($nombre);
            $repuesto->setDescripcion($descripcion);
            $repuesto->setCantidad((int)$cantidad);

            $entityManager->flush();

            return $this->redirectToRoute('repuestos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repuestos/edit.html.twig', [
            'repuesto' => $repuesto,
        ]);
    }

    #[Route('/{id}', name: 'repuestos_delete', methods: ['POST'])]
    public function delete(Request $request, Repuestos $repuesto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repuesto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repuesto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('repuestos_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/update/{id}/{field}', name: 'repuestos_update', methods: ['POST'])]
    public function update(Request $request, int $id, string $field, RepuestosRepository $repuestosRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $repuesto = $repuestosRepository->find($id);
        if (!$repuesto) {
            return new JsonResponse(['success' => false, 'message' => 'Repuesto no encontrado.']);
        }

        $data = json_decode($request->getContent(), true);
        $newValue = $data['value'] ?? null;

        switch ($field) {
            case 'nombre':
                $repuesto->setNombre($newValue);
                break;
            case 'descripcion':
                $repuesto->setDescripcion($newValue);
                break;
            case 'cantidad':
                $repuesto->setCantidad((int) $newValue);
                break;
            default:
                return new JsonResponse(['success' => false, 'message' => 'Campo no válido.']);
        }

        try {
            $entityManager->flush();
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Error al guardar los cambios.']);
        }
    }
}