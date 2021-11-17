<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\AddTrackType;
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
        ]);
    }

    /**
     * @Route("/deleteTrack/{id}", name="deleteTrack")
     */
    public function delete($id, EntityManagerInterface $manager, TrackRepository $repo){
        $track = $repo->find($id);

        $manager->remove($track);
        $manager->flush();

        return $this->redirectToRoute('admin_tracks');
    }
}
