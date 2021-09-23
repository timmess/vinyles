<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function index($id, UserRepository $repository): Response
    {
        $user = $repository->find($id);


        return $this->render('profil/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/updateUser/{id}", name="updateUser")
     */
    public function updateUser(Request $request, EntityManagerInterface $manager, UserRepository $repo, $id): Response
    {
        $user = $repo->find($id);

        $update_user_form = $this->createForm(UserType::class, $user);

        $update_user_form->handleRequest($request);

        if ($update_user_form->isSubmitted()){
            $user = $update_user_form->getData();

            $manager->persist($user);

            $manager->flush();

            return $this->redirectToRoute('profil', [
                'id'    => $id
            ]);
        }

        return $this->render('forms/user_form.html.twig', [
            'form' => $update_user_form->createView(),
        ]);
    }
}
