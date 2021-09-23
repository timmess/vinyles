<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\GenreRepository;
use App\Repository\TrackRepository;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RubricController extends AbstractController
{
    /**
     * @Route("/vinyls", name="vinyls")
     */
    public function indexVinyls(VinylRepository $vinylRepo, ArtistRepository $artistRepo): Response
    {
        $artists = $artistRepo->findAll();

        return $this->render('rubric/vinyls.html.twig', [
            'artists' => $artists
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
    public function indexAlbums(AlbumRepository $albumRepository, ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findAll();
        $albums = $albumRepository->findAll();
        return $this->render('rubric/albums.html.twig', [
            'albums' => $albums,
            'artists' => $artists,
        ]);
    }

    /**
     * @Route("/genres", name="genres")
     */
    public function indexGenres(GenreRepository $repo): Response
    {
        $genres = $repo->findAll();
        return $this->render('rubric/genres.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/tracks", name="tracks")
     */
    public function indexTracks(TrackRepository $repo): Response
    {
        $tracks = $repo->findAll();
        return $this->render('rubric/tracks.html.twig', [
            'tracks' => $tracks,
        ]);
    }
}
