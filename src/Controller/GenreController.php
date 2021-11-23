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
     * @Route("/addGenre", name="add_genre")
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
                $genre->getName() . 'a bien été ajouté !'
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
     * @Route("/updateGenre/{id}", name="updateGenre")
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
                $genre->getName() . 'a bien été modifié !'
            );

            return $this->redirectToRoute('genre', [
                'id'    => $id
            ]);
        }

        $title = "Mettre à jour " . $genre->getName();

        return $this->render('forms/genre_form.html.twig', [
            'form' => $update_genre_form->createView(),
            'title' => $title
        ]);
    }

    /**
     * @Route("/deleteGenre/{id}", name="deleteGenre")
     */
    public function delete($id, EntityManagerInterface $manager, GenreRepository $repo){
        $genre = $repo->find($id);

        $manager->remove($genre);
        $manager->flush();

        $this->addFlash(
            'success',
            $genre->getName() . ' a bien été supprimé !'
        );

        return $this->redirectToRoute('genres');
    }
}
