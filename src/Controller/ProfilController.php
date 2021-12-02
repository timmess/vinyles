<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\VinylRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

        $collection_price = 0;

        foreach ($user_vinyls as $vinyl){
            $collection_price = $collection_price + $vinyl->getPrice();
        }

        $user_vinyls = $paginator->paginate(
            $user_vinyls,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('profil/collection.html.twig', [
            'user'              => $user,
            'user_vinyls'       => $user_vinyls,
            'collection_price'  => $collection_price
        ]);
    }


    /**
     * @Route("/updateUser/{id}", name="updateUser")
     */
    public function updateUser(Request $request, EntityManagerInterface $manager, UserRepository $repo, $id, SluggerInterface $slugger): Response
    {
        $user = $repo->find($id);

        $update_user_form = $this->createForm(UserType::class, $user);

        $update_user_form->handleRequest($request);

        //        TODO: Create a service Uploadfile
        /**
         * Upload file
         *
         ** @var UploadedFile $userPhoto
         */
        $userPhoto = $update_user_form->get('photo')->getData();

        if ($userPhoto) {
            $originalFilename = pathinfo($userPhoto->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$userPhoto->guessExtension();

            // Move the file to the directory where photos are stored
            try {
                $userPhoto->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );

            } catch (FileException $e) {
                return $this->render('security/register.html.twig', [
                    'registrationForm'  => $update_user_form->createView(),
                    'errors'            => $e->getMessage(),
                ]);
            }

            // updates the 'photoFilename' property to store the PDF file name
            // instead of its contents
            $user->setPhoto($newFilename);
        }

        if ($update_user_form->isSubmitted() && $update_user_form->isValid()){
            $user = $update_user_form->getData();

            $manager->persist($user);

            $manager->flush();

            $this->addFlash(
                'success',
                'Votre profil a bien été modifié !'
            );

            return $this->redirectToRoute('profil', [
                'id'    => $id
            ]);
        }

        return $this->render('forms/user_form.html.twig', [
            'form' => $update_user_form->createView(),
        ]);
    }
}
