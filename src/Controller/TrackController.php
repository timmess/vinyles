<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\AddTrackType;
use App\Repository\AlbumRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackController extends AbstractController
{
    /**
     * @Route("/addTrack", name="add_track")
     */
    public function addTrack(Request $request, EntityManagerInterface $manager): Response
    {
        $track = new Track();

        $new_track_form = $this->createForm(AddTrackType::class, $track)->handleRequest($request);

        if ($new_track_form->isSubmitted() && $new_track_form->isValid()){
            $track = $new_track_form->getData();

            $manager->persist($track);

            $manager->flush();

            $this->addFlash(
                'success',
                'La chanson ' . $track->getName() . ' de ' . $track->getArtist()->getName() . 'a bien été ajoutée !'
            );

            return $this->redirectToRoute('track', [
                'id' => $track->getId(),
            ]);
        }

        return $this->render('forms/track_form.html.twig', [
            'form' =>$new_track_form->createView()
        ]);
    }

    /**
     * @Route("/updateTrack/{id}", name="updateTrack")
     */
    public function updateTrack(Request $request, EntityManagerInterface $manager, TrackRepository $repo, $id): Response
    {
        $track = $repo->find($id);

        $update_track_form = $this->createForm(AddTrackType::class, $track);

        $update_track_form->handleRequest($request);

        $album = $track->getAlbum();

        if ($update_track_form->isSubmitted() && $update_track_form->isValid()){
            $track = $update_track_form->getData();

            $manager->persist($track);

            $manager->flush();

            return $this->redirectToRoute('track', [
                'id'    => $id
            ]);
        }

        return $this->render('forms/track_form.html.twig', [
            'form' => $update_track_form->createView(),
            'album' => $album
        ]);
    }

    /**
     * @Route("/addTrackByAlbum/{id}", name="addTrackByAlbum")
     */
    public function addTrackByAlbum($id, EntityManagerInterface $manager, AlbumRepository $albumRepo, Request $request)
    {
        $track = new Track();

        $album = $albumRepo->find($id);

        $track->setAlbum($album);

        $add_track_by_album_type = $this->createForm(AddTrackType::class, $track)->handleRequest($request);

        if ($add_track_by_album_type->isSubmitted() && $add_track_by_album_type->isValid())
        {
            $track = $add_track_by_album_type->getData();

            $manager->persist($track);

            $manager->flush();

            $this->addFlash(
                'success',
                'La chanson ' . $track->getTitle() . 'de ' . $track->getAlbum()->getArtist()->getName() . ' a bien été ajoutée sur l\'album ' . $track->getAlbum()->getName() . ' !'
            );

            return $this->redirectToRoute('album', [
                'id' => $id,
                'album' => $album
            ]);
        }

        return $this->render('forms/track_form.html.twig', [
            'form' =>$add_track_by_album_type->createView(),
            'album' => $album,
        ]);
    }

    /**
     * @Route("/deleteTrack/{id}", name="deleteTrack")
     */
    public function delete($id, EntityManagerInterface $manager, TrackRepository $repo){
        $track = $repo->find($id);
        $albumId = $track->getAlbum()->getId();

        $manager->remove($track);
        $manager->flush();

        $this->addFlash(
            'success',
            'La chanson ' . $track->getTitle() . ' de ' . $track->getAlbum()->getArtist()->getName() . ' sur l\'album ' . $track->getAlbum()->getName() . ' a bien été supprimée !'
        );

        return $this->redirectToRoute('album', [
            'id' => $albumId
        ]);
    }
}
