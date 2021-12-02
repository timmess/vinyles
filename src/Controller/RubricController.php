<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Repository\GenreRepository;
use App\Repository\TrackRepository;
use App\Repository\VinylRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RubricController extends AbstractController
{
    /**
     * Permet d'afficher la page regroupant les vinyls
     *
     * @Route("/vinyls", name="vinyls")
     *
     * @param VinylRepository $vinylRepo
     * @param ArtistRepository $artistRepo
     *
     * @return Response
     */
    public function indexVinyls(VinylRepository $vinylRepo, ArtistRepository $artistRepo): Response
    {
        $artists = $artistRepo->findAll();

        return $this->render('rubric/vinyls.html.twig', [
            'artists' => $artists
        ]);
    }


    /**
     * Permet d'afficher la page regroupant les artistes
     *
     * @Route("/artists", name="artists")
     *
     * @param ArtistRepository $repo
     * @param Request $request
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function indexArtists(ArtistRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $artists = $repo->findAll();

        $artists = $paginator->paginate(
            $artists,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('rubric/artists.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * Permet d'afficher la page regroupant les albums
     *
     * @Route("/albums", name="albums")
     *
     * @param AlbumRepository $albumRepository
     * @param ArtistRepository $artistRepository
     *
     * @return Response
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
     * Permet d'afficher la page regroupant les genres
     *
     * @Route("/genres", name="genres")
     *
     * @param GenreRepository $repo
     * @param Request $request
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function indexGenres(GenreRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $genres = $repo->findAll();
        $genres = $paginator->paginate(
            $genres,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('rubric/genres.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * Permet d'afficher la page regroupant les chansons
     *
     * @Route("/tracks", name="tracks")
     *
     * @param TrackRepository $repo
     *
     * @return Response
     */
    public function indexTracks(TrackRepository $repo): Response
    {
        $tracks = $repo->findAll();
        return $this->render('rubric/tracks.html.twig', [
            'tracks' => $tracks,
        ]);
    }
}
