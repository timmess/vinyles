<?php

namespace App\Controller;

use App\Entity\Vinyl;
use App\Form\AddVinyleType;
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

        if ($new_vinyl_form->isSubmitted()){
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

        if ($update_vinyl_form->isSubmitted()){
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
}
