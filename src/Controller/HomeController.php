<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(VinylRepository $vinylRepo, AlbumRepository $albumRepository): Response
    {
        $user = $this->getUser();

        $last_vinyls = $vinylRepo->findLastsVinyls(3);

        $all_albums = $albumRepository->findAll();

        shuffle($all_albums);

        $albums = array_slice($all_albums, 3, 3);

        return $this->render('home/index.html.twig', [
            'user'          => $user,
            'last_vinyls'   => $last_vinyls,
            'albums'        => $albums
        ]);
    }
}
