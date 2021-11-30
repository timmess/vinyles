<?php

namespace App\Controller;

use App\Entity\Vinyl;
use App\Form\AddVinyleType;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
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

            $this->addFlash(
                'success',
                'Le vinyl ' . $vinyl->getTitle() . ' de ' . $vinyl->getArtist()->getName() . 'a bien été ajouté !'
            );

            return $this->redirectToRoute('vinyl', [
                'id' => $vinyl->getId(),
            ]);
        }

        return $this->render('forms/vinyl_form.html.twig', [
            'form' =>$new_vinyl_form->createView(),
            'title' => 'Ajouter un vinyl'
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

            $this->addFlash(
                'success',
                'Le vinyl ' . $vinyl->getTitle() . ' de ' . $vinyl->getArtist()->getName() . ' a bien été modifié !'
            );

            return $this->redirectToRoute('vinyl', [
                'id'    => $id
            ]);
        }

        $title = "Mettre à jour " . $vinyl->getTitle();

        return $this->render('forms/vinyl_form.html.twig', [
            'form' => $update_vinyl_form->createView(),
            'title' => $title,
            'vinyl' => $vinyl
        ]);
    }

    /**
     * @Route("/deleteVinyl/{id}", name="deleteVinyl")
     */
    public function delete($id, EntityManagerInterface $manager, VinylRepository $repo){
        $vinyl = $repo->find($id);

        $manager->remove($vinyl);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le vinyl ' . $vinyl->getTitle() . ' de ' . $vinyl->getArtist()->getName() . ' a bien été supprimé !'
        );

        return $this->redirectToRoute('album', [
            'id' => $vinyl->getAlbum()->getId()
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

            $this->addFlash(
                'success',
                'Le vinyl de ' . $vinyl->getArtist()->getName() . ' a bien été ajouté à l\'album ' . $vinyl->getAlbum()->getName() . ' !'
            );

            return $this->redirectToRoute('album', [
                'id' => $id
            ]);
        }

        return $this->render('forms/vinyl_by_artist_form.html.twig', [
            'form' =>$add_vinyl_by_album_type->createView(),
            'album'=> $album
        ]);
    }

    /**
     * @Route("/addVinylToUserCollection/{id}/{userId}", name="addVinylToUserCollection")
     */
    public function addVinylToUserCollection($userId, $id, VinylRepository $vinylRepository, UserRepository $userRepository, EntityManagerInterface $manager){
        $vinyl = $vinylRepository->find($id);

        $user = $userRepository->find($userId);

        $user->addVinyl($vinyl);

        $manager->persist($user);

        $manager->flush();

        $this->addFlash(
            'success',
            'Le vinyl ' . $vinyl->getTitle() . ' de ' . $vinyl->getArtist()->getName() . ' a bien ajouté de votre collection !'
        );

        return $this->redirectToRoute('collection', [
            'id'    => $userId
        ]);
    }

    /**
     * @Route("/removeVinylFromUserCollection/{id}/{userId}", name="removeVinylFromUserCollection")
     */
    public function removeVinylFromUserCollection($userId, $id, VinylRepository $vinylRepository, UserRepository $userRepository, EntityManagerInterface $manager){
        $vinyl = $vinylRepository->find($id);
        $user = $userRepository->find($userId);
        $user->removeVinyl($vinyl);

        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le vinyl ' . $vinyl->getTitle() . ' de ' . $vinyl->getArtist()->getName() . ' a bien retiré de votre collection !'
        );

        return $this->redirectToRoute('collection', [
            'id' => $user->getId()
        ]);
    }
}
