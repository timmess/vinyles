<?php

namespace App\Controller;

use App\Entity\Vinyl;
use App\Form\AddVinyleType;
use App\Repository\AlbumRepository;
use App\Repository\VinylRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VinylController extends AbstractController
{
    /**
     * @Route("/addVinyle", name="add_vinyl")
     */
    public function addVinyl(Request $request, EntityManagerInterface $manager): Response
    {
        $vinyl = new Vinyl();

        $new_vinyl_form = $this->createForm(AddVinyleType::class, $vinyl)->handleRequest($request);

        if ($new_vinyl_form->isSubmitted() && $new_vinyl_form->isValid()){
            $vinyl = $new_vinyl_form->getData();

            $manager->persist($vinyl);

            $manager->flush();

            return $this->redirectToRoute('vinyl', [
                'id' => $vinyl->getId(),
            ]);
        }

        return $this->render('forms/vinyl_form.html.twig', [
            'form' =>$new_vinyl_form->createView()
        ]);
    }

    /**
     * @Route("/updateVinyl/{id}", name="update_vinyl")
     */
    public function updateVinyl(Request $request, EntityManagerInterface $manager, VinylRepository $repo, $id): Response
    {
        $vinyl = $repo->find($id);

        $update_vinyl_form = $this->createForm(AddVinyleType::class, $vinyl);

        $update_vinyl_form->handleRequest($request);

        if ($update_vinyl_form->isSubmitted() && $update_vinyl_form->isValid()){
            $vinyl = $update_vinyl_form->getData();

            $manager->persist($vinyl);

            $manager->flush();

            return $this->redirectToRoute('vinyl', [
                'id'    => $id
            ]);
        }

        return $this->render('admin/forms/form.html.twig', [
            'form' => $update_vinyl_form->createView(),
        ]);
    }

    /**
     * @Route("/deleteVinyl/{id}", name="deleteVinyl")
     */
    public function delete($id, EntityManagerInterface $manager, VinylRepository $repo){
        $vinyl = $repo->find($id);

        $manager->remove($vinyl);
        $manager->flush();

        return $this->redirectToRoute('admin_vinyls');
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
}
