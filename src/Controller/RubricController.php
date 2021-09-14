<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RubricController extends AbstractController
{
    /**
     * @Route("/vinyls", name="vinyls")
     */
    public function indexVinyls(VinylRepository $repo): Response
    {
        $vinyls = $repo->findAll();
        return $this->render('rubric/vinyls.html.twig', [
            'vinyls' => $vinyls,
        ]);
    }


    /**
     * @Route("/artists", name="artists")
     */
    public function indexArtists(ArtistRepository $repo): Response
    {
        $artists = $repo->findAll();
        return $this->render('rubric/artists.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @Route("/albums", name="albums")
     */
    public function indexAlbums(AlbumRepository $repo): Response
    {
        $albums = $repo->findAll();
        return $this->render('rubric/albums.html.twig', [
            'albums' => $albums,
        ]);
    }
}
