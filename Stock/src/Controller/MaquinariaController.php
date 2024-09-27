<?php

namespace App\Controller;

use App\Entity\Maquinaria;
use App\Repository\MaquinariaRepository;
use App\Repository\RepuestosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request, MaquinariaRepository $maquinariaRepository, RepuestosRepository $repuestosRepository, EntityManagerInterface $entityManager): Response
    {
    if ($request->isMethod('POST')) {
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

        // Validación de stock de repuestos
        $repuestosMaquinaria = $maquinaria->getRecetas(); // Suponiendo que la maquinaria está asociada a repuestos a través de "Recetas"
        $validacionFallida = false; // Bandera para impedir agregar maquinaria si no hay stock suficiente

        foreach ($repuestosMaquinaria as $receta) {
            foreach ($receta->getRepuestos() as $repuesto) {
                if (!$repuesto->esStockSuficiente()) {
                    $this->addFlash('danger', "No hay suficientes unidades de {$repuesto->getNombre()} en stock.");
                    $validacionFallida = true;
                } elseif ($repuesto->necesitaReabastecimiento()) {
                    $this->addFlash('warning', "El stock de {$repuesto->getNombre()} es bajo. Considera reabastecer.");
                }
            }
        }

        // Si la validación de repuestos falló, no se guarda la maquinaria
        if ($validacionFallida) {
            return $this->redirectToRoute('maquinaria_new');
        }

        // Si todo está correcto, guardar la maquinaria
        $entityManager->persist($maquinaria);
        $entityManager->flush();

        $this->addFlash('success', 'Maquinaria agregada con éxito.');
        return $this->redirectToRoute('maquinaria_index');
    }

    // Renderizar el formulario si es GET o si la validación falló
    return $this->render('Maquinarias/Maquinaria.html.twig', [
        'maquinarias' => $maquinariaRepository->findAll(),
    ]);
    }

    
    #[Route('/{id}/delete', name: 'maquinaria_delete', methods: ['POST'])]
    public function delete(Request $request, Maquinaria $maquinaria, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $maquinaria->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maquinaria);
            $entityManager->flush();

            $this->addFlash('success', 'La maquinaria ha sido eliminada con éxito.');
        }

        return $this->redirectToRoute('maquinaria_index');
    }

    #[Route('/{id}/edit', name: 'maquinaria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maquinaria $maquinaria, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $maquinaria->setCantidad((int) $request->request->get('cantidad'));
            $entityManager->flush();

            return $this->redirectToRoute('maquinaria_index');
        }

        return $this->render('Maquinarias/edit.html.twig', [
            'maquinaria' => $maquinaria,
        ]);
    }
}
