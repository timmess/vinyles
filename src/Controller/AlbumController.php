<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Vinyl;
use App\Form\AddAlbumType;
use App\Form\AddVinyleType;
use App\Repository\AlbumRepository;
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

            return $this->redirectToRoute('album', [
                'id'    => $id
            ]);
        }

        return $this->render('forms/album_form.html.twig', [
            'form' => $update_album_form->createView(),
        ]);
    }

    /**
     * @Route("/addVinylByAlbum/{id}", name="addVinylByAlbum")
     */
    public function addVinylByAlbum($id, EntityManagerInterface $manager, AlbumRepository $albumRepo, Request $request)
    {
        $vinyl = new Vinyl();

        $album = $albumRepo->find($id);

        $vinyl  ->setAlbum($album)
                ->setTitle($album->getName())
                ->setPhoto(($album->getPhoto()))
                ->setArtist($album->getArtist());

        $add_vinyl_by_album_type = $this->createForm(AddVinyleType::class, $vinyl)->handleRequest($request);

        if ($add_vinyl_by_album_type->isSubmitted() && $add_vinyl_by_album_type->isValid())
        {
            $vinyl = $add_vinyl_by_album_type->getData();

            $manager->persist($vinyl);

            $manager->flush();

            return $this->redirectToRoute('album', [
                'id' => $id
            ]);
        }

        return $this->render('forms/vinyl_form.html.twig', [
            'form' =>$add_vinyl_by_album_type->createView()
        ]);
    }

    /**
     * @Route("/deleteAlbum/{id}", name="deleteAlbum")
     */
    public function delete($id, EntityManagerInterface $manager, AlbumRepository $repo){
        $album = $repo->find($id);

        $manager->remove($album);
        $manager->flush();

        return $this->redirectToRoute('admin_artists');
    }
}
