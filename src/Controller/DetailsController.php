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

class DetailsController extends AbstractController
{
    /**
     * Permet d'afficher la page d'un vinyle
     *
     * @Route("/vinyle/{id}", name="vinyl")
     *
     * @param VinylRepository $repo
     * @param $id
     *
     * @return Response
     */
    public function vinylDetail(VinylRepository $repo, $id): Response
    {
        $user = false;
        $userVinyls = null;

        $vinyl = $repo->find($id);

        if ($this->getUser()){
            $userVinyls = $this->getUser()->getVinyls();
        }

        return $this->render('details/vinyl.html.twig', [
            'vinyl' => $vinyl,
            'userVinyls' => $userVinyls
        ]);
    }

    /**
     * Permet d'afficher la page d'une chanson
     *
     * @Route("/track/{id}", name="track")
     *
     * @param TrackRepository $repo
     * @param $id
     *
     * @return Response
     */
    public function trackDetail(TrackRepository $repo, $id): Response
    {
        $track = $repo->find($id);
        return $this->render('details/track.html.twig', [
            'track' => $track
        ]);
    }

    /**
     * Permet d'afficher la page d'un album
     *
     * @Route("/album/{id}", name="album")
     *
     * @param AlbumRepository $repo
     * @param $id
     *
     * @return Response
     */
    public function albumDetail(AlbumRepository $repo, $id): Response
    {
        $album = $repo->find($id);
        return $this->render('details/album.html.twig', [
            'album' => $album
        ]);
    }

    /**
     * Permet d'afficher la page d'un artiste
     *
     * @Route("/artist/{id}", name="artist")
     *
     * @param ArtistRepository $repo
     * @param $id
     *
     * @return Response
     */
    public function artistDetail(ArtistRepository $repo, $id): Response
    {
        $artist = $repo->find($id);
        return $this->render('details/artist.html.twig', [
            'artist' => $artist
        ]);
    }

    /**
     * Permet d'afficher la page d'un genre
     *
     * @Route("/genre/{id}", name="genre")
     *
     * @param GenreRepository $repo
     * @param $id
     * @param PaginatorInterface $paginator
     * @param Request $request
     *
     * @return Response
     */
    public function genreDetail(GenreRepository $repo, $id, PaginatorInterface $paginator, Request $request): Response
    {
        $genre = $repo->find($id);

        $artists = $genre->getArtists();

        $artists = $paginator->paginate(
            $artists,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('details/genre.html.twig', [
            'genre'     => $genre,
            'artists'   => $artists
        ]);
    }
}
