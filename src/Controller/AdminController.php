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

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [

        ]);
    }

    /**
     * @Route("/admin/artists", name="admin_artists")
     */
    public function artists(ArtistRepository $artistRepo): Response
    {
        $artists = $artistRepo->findAll();

        return $this->render('admin/artists/index.html.twig', [
            'artists' => $artists
        ]);
    }

    /**
     * @Route("/admin/genres", name="admin_genres")
     */
    public function genres(GenreRepository $repo): Response
    {
        $genres = $repo->findAll();

        return $this->render('admin/genres/index.html.twig', [
            'genres' => $genres
        ]);
    }

    /**
     * @Route("/admin/vinyls", name="admin_vinyls")
     */
    public function vinyls(VinylRepository $repo): Response
    {
        $vinyls = $repo->findAll();

        return $this->render('admin/vinyls/index.html.twig', [
            'vinyls' => $vinyls
        ]);
    }

    /**
     * @Route("/admin/albums", name="admin_albums")
     */
    public function albums(AlbumRepository $repo): Response
    {
        $albums = $repo->findAll();

        return $this->render('admin/albums/index.html.twig', [
            'albums' => $albums
        ]);
    }

    /**
     * @Route("/admin/tracks", name="admin_tracks")
     */
    public function tracks(TrackRepository $repo): Response
    {
        $tracks = $repo->findAll();

        return $this->render('admin/tracks/index.html.twig', [
            'tracks' => $tracks
        ]);
    }
}
