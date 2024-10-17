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
        $recetas = $recetaRepository->findAll();

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
            $maquinariasSeleccionadas = $request->request->all('maquinarias');
            foreach ($maquinariasSeleccionadas as $maquinariaId) {
                $maquinaria = $maquinariaRepository->find($maquinariaId);
                if ($maquinaria) {
                    $receta->addMaquinaria($maquinaria);
                }
            }

            // Obtener repuestos seleccionados desde el formulario
            $repuestosSeleccionados = $request->request->all('repuestos');
            foreach ($repuestosSeleccionados as $repuestoId) {
                $repuesto = $repuestosRepository->find($repuestoId);
                if ($repuesto) {
                    // Descontar la cantidad de repuestos utilizados
                    if ($repuesto->getCantidad() > 0) {
                        $repuesto->setCantidad($repuesto->getCantidad() - 1); // Ajustar según la cantidad utilizada
                        $receta->addRepuesto($repuesto);

                        // Verificar si el repuesto necesita reabastecimiento
                        if ($repuesto->necesitaReabastecimiento()) {
                            $this->addFlash('warning', 'El repuesto ' . $repuesto->getNombre() . ' necesita reabastecimiento.');
                        }
                    } else {
                        $this->addFlash('danger', 'No hay suficiente stock del repuesto ' . $repuesto->getNombre() . '.');
                        return $this->redirectToRoute('receta_new');
                    }
                }
            }

            // Guardar la nueva receta
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
            // Actualizar las maquinarias seleccionadas
            $receta->getMaquinarias()->clear(); 
            $maquinariasSeleccionadas = $request->request->all('maquinarias');
            foreach ($maquinariasSeleccionadas as $maquinariaId) {
                $maquinaria = $maquinariaRepository->find($maquinariaId);
                if ($maquinaria) {
                    $receta->addMaquinaria($maquinaria);
                }
            }

            // Actualizar los repuestos seleccionados
            $receta->getRepuestos()->clear();
            $repuestosSeleccionados = $request->request->all('repuestos');
            foreach ($repuestosSeleccionados as $repuestoId) {
                $repuesto = $repuestosRepository->find($repuestoId);
                if ($repuesto) {
                    // Descontar cantidad de repuestos si es necesario
                    if ($repuesto->getCantidad() > 0) {
                        $repuesto->setCantidad($repuesto->getCantidad() - 1);
                        $receta->addRepuesto($repuesto);

                        // Verificar si necesita reabastecimiento
                        if ($repuesto->necesitaReabastecimiento()) {
                            $this->addFlash('warning', 'El repuesto ' . $repuesto->getNombre() . ' necesita reabastecimiento.');
                        }
                    } else {
                        $this->addFlash('danger', 'No hay suficiente stock del repuesto ' . $repuesto->getNombre() . '.');
                        return $this->redirectToRoute('receta_edit', ['id' => $receta->getId()]);
                    }
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

