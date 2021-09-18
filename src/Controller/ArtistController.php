<?php

namespace App\Controller;

use App\Entity\Artist;
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

        if ($new_artist_form->isSubmitted()){
            $artist = $new_artist_form->getData();

            $manager->persist($artist);

            $manager->flush();

            return $this->redirectToRoute('artist', [
               'id' => $artist->getId(),
            ]);
        }

        return $this->render('admin/forms/form.html.twig', [
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

        if ($update_artist_form->isSubmitted()){
            $artist = $update_artist_form->getData();

            $manager->persist($artist);

            $manager->flush();

            return $this->redirectToRoute('artist', [
                'id'    => $id
            ]);
        }

        return $this->render('admin/forms/form.html.twig', [
            'form' => $update_artist_form->createView(),
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
