<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Vinyl;
use App\Form\AddAlbumType;
use App\Form\AddVinyleType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /**
     * @Route("/addAlbum", name="add_album")
     */
    public function addAlbum(Request $request, EntityManagerInterface $manager): Response
    {
        $album = new Album();

        $new_album_form = $this->createForm(AddAlbumType::class, $album)->handleRequest($request);

        if ($new_album_form->isSubmitted() && $new_album_form->isValid()){
            $album = $new_album_form->getData();

            $manager->persist($album);

            $manager->flush();

            $this->addFlash(
                'success',
                'L\'album ' . $album->getName() . ' de ' . $album->getArtist()->getName() . 'a bien été ajouté !'
            );

            return $this->redirectToRoute('album', [
                'id' => $album->getId(),
            ]);
        }

        return $this->render('forms/album_form.html.twig', [
            'form' =>$new_album_form->createView()
        ]);
    }

    /**
     * @Route("/updateAlbum/{id}", name="updateAlbum")
     */
    public function updateAlbum(Request $request, EntityManagerInterface $manager, AlbumRepository $repo, $id): Response
    {
        $album = $repo->find($id);

        $update_album_form = $this->createForm(AddAlbumType::class, $album);

        $update_album_form->handleRequest($request);

        if ($update_album_form->isSubmitted() && $update_album_form->isValid()){
            $album = $update_album_form->getData();

            $manager->persist($album);

            $manager->flush();

            $this->addFlash(
                'success',
                'L\'album ' . $album->getName() . ' de ' . $album->getArtist()->getName() . ' a bien été mis à jour !'
            );

            return $this->redirectToRoute('album', [
                'id'    => $id,
            ]);
        }

        return $this->render('forms/update_album_form.html.twig', [
            'form' => $update_album_form->createView(),
            'album' => $album,
        ]);
    }

    /**
     * @Route("/deleteAlbum/{id}", name="deleteAlbum")
     */
    public function deleteAlbum($id, EntityManagerInterface $manager, AlbumRepository $repo){
        $album = $repo->find($id);

        $manager->remove($album);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'album ' . $album->getName() .  ' a bien été supprimé !'
        );

        return $this->redirectToRoute('artist', [
            'id' => $album->getArtist()->getId()
        ]);
    }

    /**
     * @Route("/addAlbumByArtist/{id}", name="addAlbumByArtist")
     */
    public function addAlbumByArtist($id, EntityManagerInterface $manager, ArtistRepository $artistRepo, Request $request)
    {
        $album = new Album();

        $artist = $artistRepo->find($id);
        $album->setArtist($artist);

        $add_album_by_artist_type = $this->createForm(AddAlbumType::class, $album)->handleRequest($request);

        if ($add_album_by_artist_type->isSubmitted() && $add_album_by_artist_type->isValid())
        {
            $album = $add_album_by_artist_type->getData();

            $manager->persist($album);

            $manager->flush();

            $this->addFlash(
                'success',
                'L\'album ' . $album->getName() . ' a bien été ajouté à ' . $artist->getName() . ' !'
            );

            return $this->redirectToRoute('album', [
                'id' => $album->getId()
            ]);
        }

        return $this->render('forms/album_by_artist_form.html.twig', [
            'form' =>$add_album_by_artist_type->createView(),
            'artist' => $artist,
        ]);
    }
}
