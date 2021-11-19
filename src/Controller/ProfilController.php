<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\VinylRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
            'user'          => $user
        ]);
    }

    /**
     * @Route("/profil/{id}/collection", name="collection")
     */
    public function collection($id, UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $repository->find($id);

        $user_vinyls = $user->getVinyls();

        $user_vinyls = $paginator->paginate(
            $user_vinyls,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('profil/collection.html.twig', [
            'user'          => $user,
            'user_vinyls'   => $user_vinyls
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

        if ($update_user_form->isSubmitted() && $update_user_form->isValid()){
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

    /**
     * @Route("/addVinylToUserCollection/{id}/{userId}", name="addVinylToUserCollection")
     */
    public function addVinylToUserCollection($userId, $id, VinylRepository $vinylRepository, UserRepository $userRepository, EntityManagerInterface $manager){
        $vinyl = $vinylRepository->find($id);

        $user = $userRepository->find($userId);

        $user->addVinyl($vinyl);

        $manager->persist($user);

        $manager->flush();

        return $this->redirectToRoute('collection', [
            'id'    => $userId
        ]);
    }
}
