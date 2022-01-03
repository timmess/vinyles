<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artist;
use App\Form\AddAlbumType;
use App\Form\AddArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArtistController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire d'ajout d'un artiste
     *
     * @Route("/addArtist", name="add_artist")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function addArtist(Request $request, EntityManagerInterface $manager): Response
    {
        $artist = new Artist();

        $new_artist_form = $this->createForm(AddArtistType::class, $artist)->handleRequest($request);

        if ($new_artist_form->isSubmitted() && $new_artist_form->isValid()){
            $artist = $new_artist_form->getData();

            $manager->persist($artist);

            $manager->flush();

            $this->addFlash(
                'success',
                $artist->getName() . 'a bien été ajouté !'
            );

            return $this->redirectToRoute('artist', [
               'id' => $artist->getId(),
            ]);
        }

        return $this->render('forms/artist_form.html.twig', [
            'form' =>$new_artist_form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de gérer le formulaire de modification d'un artiste
     *
     * @Route("/updateArtist/{id}", name="updateArtist")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ArtistRepository $repo
     * @param $id
     * @param SluggerInterface $slugger
     *
     * @return Response
     */
    public function updateArtist(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger, ArtistRepository $repo, $id): Response
    {
        $artist = $repo->find($id);

        $update_artist_form = $this->createForm(AddArtistType::class, $artist);

        $update_artist_form->handleRequest($request);

        /**
         * Upload file
         *
         ** @var UploadedFile $artistPhoto
         */
        $artistPhoto = $update_artist_form->get('photo')->getData();

        if ($artistPhoto) {
            $originalFilename = pathinfo($artistPhoto->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$artistPhoto->guessExtension();

            // Move the file to the directory where photos are stored
            try {
                $artistPhoto->move(
                    $this->getParameter('artist_photos_directory'),
                    $newFilename
                );

            } catch (FileException $e) {
                return $this->render('security/register.html.twig', [
                    'registrationForm'  => $update_artist_form->createView(),
                    'errors'            => $e->getMessage(),
                ]);
            }

            // updates the 'photoFilename' property to store the PDF file name
            // instead of its contents
            $artist->setPhoto($newFilename);
        }

        if ($update_artist_form->isSubmitted() and $update_artist_form->isValid()){
            $artist = $update_artist_form->getData();

            $manager->persist($artist);

            $manager->flush();

            $this->addFlash(
                'success',
                $artist->getName() . ' a bien été modifié !'
            );

            return $this->redirectToRoute('artist', [
                'id'    => $id
            ]);
        }

        return $this->render('forms/update_artist_form.html.twig', [
            'form'      => $update_artist_form->createView(),
            'artist'    => $artist
        ]);
    }

    /**
     * Permet de gérer la suppression d'un artiste
     *
     * @Route("/deleteArtist/{id}", name="deleteArtist")
     *
     * @param $id
     * @param EntityManagerInterface $manager
     * @param ArtistRepository $repo
     *
     * @return Response
     */
    public function deleteArtist($id, EntityManagerInterface $manager, ArtistRepository $repo): Response
    {
        $artist = $repo->find($id);

        $manager->remove($artist);
        $manager->flush();

        $this->addFlash(
            'success',
            $artist->getName() . ' a bien été supprimé !'
        );

        return $this->redirectToRoute('admin_artists');
    }
}
