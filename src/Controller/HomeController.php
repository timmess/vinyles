<?php

namespace App\Controller;

use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(VinylRepository $vinylRepo): Response
    {
        $user = $this->getUser();

        $last_vinyls = $vinylRepo->findLastsVinyls(3);

        return $this->render('home/index.html.twig', [
            'user'          => $user,
            'last_vinyls'   => $last_vinyls,
        ]);
    }
}
