<?php

namespace App\Controller;

use App\Entity\Receta;
use App\Form\RecetaType;
use App\Repository\RecetaRepository;
use App\Repository\MaquinariaRepository;
use App\Repository\RepuestosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/receta')]
class ViewRecipeController extends AbstractController
{
    #[Route('/', name: 'receta_index', methods: ['GET'])]
    public function index(RecetaRepository $recetaRepository): Response
    {
        // Obtener todas las recetas de la base de datos
        $recetas = $recetaRepository->findAll();

        // Renderizar la vista de recetas
        return $this->render('view_recipe/view_recipe.html.twig', [
            'recetas' => $recetas,
        ]);
    }

    #[Route('/new', name: 'receta_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        MaquinariaRepository $maquinariaRepository, 
        RepuestosRepository $repuestosRepository
    ): Response {
        $receta = new Receta();
        $form = $this->createForm(RecetaType::class, $receta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener maquinarias seleccionadas desde el formulario
            $maquinariasSeleccionadas = $request->request->get('maquinarias', []);
            foreach ($maquinariasSeleccionadas as $maquinariaId) {
                $maquinaria = $maquinariaRepository->find($maquinariaId);
                if ($maquinaria) {
                    $receta->addMaquinaria($maquinaria);
                }
            }

            // Obtener repuestos seleccionados desde el formulario
            $repuestosSeleccionados = $request->request->get('repuestos', []);
            foreach ($repuestosSeleccionados as $repuestoId) {
                $repuesto = $repuestosRepository->find($repuestoId);
                if ($repuesto) {
                    $receta->addRepuesto($repuesto);
                }
            }

            // Persistir y guardar la nueva receta
            $entityManager->persist($receta);
            $entityManager->flush();

            $this->addFlash('success', 'Receta creada con éxito.');
            return $this->redirectToRoute('receta_index');
        }

        return $this->render('view_recipe/create_recipe.html.twig', [
            'form' => $form->createView(),
            'maquinarias' => $maquinariaRepository->findAll(),
            'repuestos' => $repuestosRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'receta_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Receta $receta, 
        EntityManagerInterface $entityManager,
        MaquinariaRepository $maquinariaRepository, 
        RepuestosRepository $repuestosRepository
    ): Response {
        $form = $this->createForm(RecetaType::class, $receta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Actualizar maquinarias seleccionadas
            $receta->getMaquinarias()->clear(); // Limpiar las maquinarias actuales
            $maquinariasSeleccionadas = $request->request->get('maquinarias', []);
            foreach ($maquinariasSeleccionadas as $maquinariaId) {
                $maquinaria = $maquinariaRepository->find($maquinariaId);
                if ($maquinaria) {
                    $receta->addMaquinaria($maquinaria);
                }
            }

            // Actualizar repuestos seleccionados
            $receta->getRepuestos()->clear(); // Limpiar los repuestos actuales
            $repuestosSeleccionados = $request->request->get('repuestos', []);
            foreach ($repuestosSeleccionados as $repuestoId) {
                $repuesto = $repuestosRepository->find($repuestoId);
                if ($repuesto) {
                    $receta->addRepuesto($repuesto);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Receta actualizada con éxito.');
            return $this->redirectToRoute('receta_index');
        }

        return $this->render('receta/edit.html.twig', [
            'receta' => $receta,
            'form' => $form->createView(),
            'maquinarias' => $maquinariaRepository->findAll(),
            'repuestos' => $repuestosRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'receta_show', methods: ['GET'])]
    public function show(Receta $receta): Response
    {
        return $this->render('receta/show.html.twig', [
            'receta' => $receta,
        ]);
    }

    #[Route('/{id}/delete', name: 'receta_delete', methods: ['POST'])]
    public function delete(Request $request, Receta $receta, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $receta->getId(), $request->request->get('_token'))) {
            $entityManager->remove($receta);
            $entityManager->flush();

            $this->addFlash('success', 'Receta eliminada con éxito.');
        }

        return $this->redirectToRoute('receta_index');
    }
}
