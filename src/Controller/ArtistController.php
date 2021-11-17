<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Form\AddAlbumType;
use App\Form\AddArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    /**
     * @Route("/addArtist", name="add_artist")
     */
    public function addArtist(Request $request, EntityManagerInterface $manager): Response
    {
        $artist = new Artist();

        $new_artist_form = $this->createForm(AddArtistType::class, $artist)->handleRequest($request);

        if ($new_artist_form->isSubmitted() && $new_artist_form->isValid()){
            $artist = $new_artist_form->getData();

            $manager->persist($artist);

            $manager->flush();

            return $this->redirectToRoute('artist', [
               'id' => $artist->getId(),
            ]);
        }

        return $this->render('forms/artist_form.html.twig', [
            'form' =>$new_artist_form->createView()
        ]);
    }

    /**
     * @Route("/updateArtist/{id}", name="updateArtist")
     */
    public function updateArtist(Request $request, EntityManagerInterface $manager, ArtistRepository $repo, $id): Response
    {
        $artist = $repo->find($id);

        $update_artist_form = $this->createForm(AddArtistType::class, $artist);

        $update_artist_form->handleRequest($request);

        if ($update_artist_form->isSubmitted() and $update_artist_form->isValid()){
            $artist = $update_artist_form->getData();

            $manager->persist($artist);

            $manager->flush();

            return $this->redirectToRoute('artist', [
                'id'    => $id
            ]);
        }

        return $this->render('forms/artist_form.html.twig', [
            'form' => $update_artist_form->createView(),
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

            return $this->redirectToRoute('artist', [
                'id' => $id
            ]);
        }

        return $this->render('forms/album_form.html.twig', [
            'form' =>$add_album_by_artist_type->createView()
        ]);
    }

    /**
     * @Route("/deleteArtist/{id}", name="deleteArtist")
     */
    public function delete($id, EntityManagerInterface $manager, ArtistRepository $repo){
        $artist = $repo->find($id);

        $manager->remove($artist);
        $manager->flush();

        return $this->redirectToRoute('admin_artists');
    }
}
