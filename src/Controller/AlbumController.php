<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AddAlbumType;
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
    public function addArtist(Request $request, EntityManagerInterface $manager): Response
    {
        $album = new Album();

        $new_album_form = $this->createForm(AddAlbumType::class, $album)->handleRequest($request);

        if ($new_album_form->isSubmitted()){
            $album = $new_album_form->getData();

            $manager->persist($album);

            $manager->flush();

            return $this->redirectToRoute('album', [
                'id' => $album->getId(),
            ]);
        }

        return $this->render('admin/forms/form.html.twig', [
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

        if ($update_album_form->isSubmitted()){
            $album = $update_album_form->getData();

            $manager->persist($album);

            $manager->flush();

            return $this->redirectToRoute('album', [
                'id'    => $id
            ]);
        }

        return $this->render('admin/forms/form.html.twig', [
            'form' => $update_album_form->createView(),
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
