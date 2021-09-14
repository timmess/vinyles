<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\TrackRepository;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsController extends AbstractController
{
    /**
     * @Route("/vinyle/{id}", name="vinyl")
     */
    public function vinylDetail(VinylRepository $repo, $id): Response
    {
        $vinyl = $repo->find($id);
        return $this->render('details/vinyl.html.twig', [
            'vinyl' => $vinyl
        ]);
    }

    /**
     * @Route("/track/{id}", name="track")
     */
    public function trackDetail(TrackRepository $repo, $id): Response
    {
        $track = $repo->find($id);
        return $this->render('details/track.html.twig', [
            'vinyl' => $track
        ]);
    }

    /**
     * @Route("/album/{id}", name="album")
     */
    public function albumDetail(AlbumRepository $repo, $id): Response
    {
        $album = $repo->find($id);
        return $this->render('details/album.html.twig', [
            'album' => $album
        ]);
    }

    /**
     * @Route("/artist/{id}", name="artist")
     */
    public function artistDetail(ArtistRepository $repo, $id): Response
    {
        $artist = $repo->find($id);
        return $this->render('details/artist.html.twig', [
            'artist' => $artist
        ]);
    }
}
