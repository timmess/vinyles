<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\AddGenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * Permet d'afficher et gérer le formulaire d'ajout de genre
     *
     * @Route("/addGenre", name="add_genre")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function addGenre(Request $request, EntityManagerInterface $manager): Response
    {
        $genre = new Genre();

        $new_genre_form = $this->createForm(AddGenreType::class, $genre)->handleRequest($request);

        if ($new_genre_form->isSubmitted() && $new_genre_form->isValid()){
            $genre = $new_genre_form->getData();

            $manager->persist($genre);

            $manager->flush();

            $this->addFlash(
                'success',
                'Le genre ' . $genre->getName() . ' a bien été ajouté !'
            );

            return $this->redirectToRoute('genre', [
                'id' => $genre->getId(),
            ]);
        }

        return $this->render('forms/genre_form.html.twig', [
            'form' =>$new_genre_form->createView(),
            'title' => 'Ajouter un genre'
        ]);
    }

    /**
     * Permet d'afficher et gérer le formulaire de modification de genre
     *
     * @Route("/updateGenre/{id}", name="updateGenre")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param GenreRepository $repo
     * @param $id
     *
     * @return Response
     */
    public function updateGenre(Request $request, EntityManagerInterface $manager, GenreRepository $repo, $id): Response
    {
        $genre = $repo->find($id);

        $update_genre_form = $this->createForm(AddGenreType::class, $genre);

        $update_genre_form->handleRequest($request);

        if ($update_genre_form->isSubmitted() && $update_genre_form->isValid()){
            $genre = $update_genre_form->getData();

            $manager->persist($genre);

            $manager->flush();

            $this->addFlash(
                'success',
                'Le genre ' . $genre->getName() . ' a bien été modifié !'
            );

            return $this->redirectToRoute('genre', [
                'id'    => $id
            ]);
        }

        $title = "Mettre à jour le genre " . $genre->getName();

        return $this->render('forms/genre_form.html.twig', [
            'form' => $update_genre_form->createView(),
            'title' => $title,
            'genre' => $genre
        ]);
    }

    /**
     * Permet de gérer la suppression d'un genre
     *
     * @Route("/deleteGenre/{id}", name="deleteGenre")
     *
     * @param $id
     * @param EntityManagerInterface $manager
     * @param GenreRepository $repo
     *
     * @return Response
     */
    public function delete($id, EntityManagerInterface $manager, GenreRepository $repo): Response
    {
        $genre = $repo->find($id);

        $manager->remove($genre);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le genre ' . $genre->getName() . ' a bien été supprimé !'
        );

        return $this->redirectToRoute('genres');
    }
}
