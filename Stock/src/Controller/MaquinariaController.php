<?php

namespace App\Controller;

use App\Entity\Maquinaria;
use App\Form\MaquinariaType;
use App\Repository\MaquinariaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/maquinaria')]
class MaquinariaController extends AbstractController
{
    // Listar todas las maquinarias
    #[Route('/', name: 'maquinaria_index', methods: ['GET'])]
    public function index(MaquinariaRepository $maquinariaRepository): Response
    {
        $maquinarias = $maquinariaRepository->findAll();

        return $this->render('Maquinarias/Maquinaria.html.twig', [
            'maquinarias' => $maquinarias,
        ]);
    }

    // Crear una nueva maquinaria
    #[Route('/new', name: 'maquinaria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $maquinaria = new Maquinaria();
        $form = $this->createForm(MaquinariaType::class, $maquinaria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Subida de imagen
            $imagen = $form->get('imagen')->getData();
            if ($imagen) {
                $imagenFilename = uniqid() . '.' . $imagen->guessExtension();
                $imagen->move($this->getParameter('images_directory'), $imagenFilename);
                $maquinaria->setImagen($imagenFilename);
            }

            // Guardar maquinaria en la base de datos
            $entityManager->persist($maquinaria);
            $entityManager->flush();

            $this->addFlash('success', 'Maquinaria agregada con éxito.');
            return $this->redirectToRoute('maquinaria_index');
        }

        return $this->render('Maquinarias/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Editar maquinaria existente
    #[Route('/{id}/edit', name: 'maquinaria_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Maquinaria $maquinaria, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(MaquinariaType::class, $maquinaria);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        $maquinaria->setNombre($request->request->get('nombre'));
        $maquinaria->setMarca($request->request->get('marca'));
        $maquinaria->setDescripcion($request->request->get('descripcion'));
        $maquinaria->setAñosUso($request->request->get('aniosUso'));
        $maquinaria->setCantidad($request->request->get('cantidad'));
        
        // Guardar cambios
        $entityManager->flush();
        $this->addFlash('success', 'Maquinaria editada con éxito.');
        return $this->redirectToRoute('maquinaria_index');
    }
    

    return $this->render('Maquinarias/edit.html.twig', [
        'form' => $form->createView(),
        'maquinaria' => $maquinaria,
    ]);
}


    // Eliminar maquinaria
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
}

