<?php
//// Tentative d'intégration des genres
//
//namespace App\DataFixtures;
//
//use App\Entity\Album;
//use App\Entity\Artist;
//use App\Entity\Genre;
//use App\Entity\Track;
//use App\Entity\Vinyl;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Persistence\ObjectManager;
//use Faker;
//
//class AppFixtures extends Fixture
//{
//    public function random_float($start_number = 0,$end_number = 1,$mul = 1000000)
//    {
//        if ($start_number > $end_number) return false;
//        return mt_rand($start_number * $mul,$end_number * $mul)/$mul;
//    }
//
//    public function load(ObjectManager $manager)
//    {
//        $faker = Faker\Factory::create();
//
//        $genres = [];
//        $artist_genres = [];
//
//        //Genre Loop
//        for ($i = 0; $i <= 3; $i++){
//            $genre = new Genre();
//
//            $genre  ->setName($faker->word);
//            $genres[] = $genre;
//        }
//
//        //Artist loop
//        for ($i = 1; $i <= 4; $i++){
//            $artist = new Artist;
//
//            $artist ->setName($faker->name)
//                    ->setPhoto($faker->imageUrl());
//
//            $p = rand(0,2);
//
//            for ($i = $p; $i<=2;$i++){
//                $artist_genres = $genres[$i];
//            }
//
//            foreach ($artist_genres as $genre){
//                $artist ->addGenre($genre);
//            }
//
//            //Album loop
//            $f = rand(1, 3);
//            for ($e = $f; $e <= 3; $e++){
//                $album = new Album();
//                $album  ->setName($faker->word)
//                        ->setPhoto($faker->imageUrl());
//                foreach ($artist_genres as $genre){
//                    $album ->addGenre($genre);
//                }
//
//                //Tracks loop
//                $u = rand(2, 12);
//                $position = 1;
//                for ($v = $u; $v <=12 ; $v++){
//                    $track = new Track();
//                    $rand = $this->random_float(1, 4);
//
//                    $track  ->setTitle($faker->word)
//                            ->setDuration(round($rand, 2))
//                            ->setAlbum($album)
//                            ->setPosition($position);
//
//                    $manager->persist($track);
//                    $position++;
//                }
//
//                //Vinyl loop
//                $n = rand(1, 5);
//                for ($a = $n; $a < 6; $a++){
//                    $vinyl = new Vinyl();
//                    $vinyl  ->setTitle($album->getName())
//                            ->setReleaseDate($faker->dateTime($max = 'now', $timezone = null))
//                            ->setAlbum($album)
//                            ->setPhoto($faker->imageUrl());
//                            foreach ($artist_genres as $genre){
//                                $vinyl ->addGenre($genre);
//                            }
//                    $artist ->addVinyl($vinyl);
//
//                    $manager->persist($vinyl);
//                }
//                $artist ->addAlbum($album);
//
//                $manager->persist($genre);
//                $manager->persist($album);
//            }
//            $manager->persist($artist);
//        }
//        $manager->flush();
//    }
//}

// Fixtures qui marchent
namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Track;
use App\Entity\Vinyl;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

//TODO: inserer plusieurs genres dans les artistes / albums.
//TODO: Donner des genres aux tracks.
class AppFixtures extends Fixture
{
    public function random_float($start_number = 0, $end_number = 1, $mul = 1000000)
    {
        if ($start_number > $end_number) return false;
        return mt_rand($start_number * $mul, $end_number * $mul) / $mul;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $genres = [];

        //Genre Loop
        for ($i = 0; $i <= 3; $i++){
            $genre = new Genre();
            $genre  ->setName($faker->word);
            $manager->persist($genre);
            $genres[] = $genre;
        }

        //Artiste loop
        for ($i = 1; $i <= 20; $i++) {
            $artist = new Artist;

            $r = rand(0,3);

            $artist ->setName($faker->name)
                    ->setPhoto($faker->imageUrl())
                    ->addGenre($genres[$r]);
            $r = rand(0,3);
            $artist->addGenre($genres[$r]);

            //Album loop
            $f = rand(1, 3);
            for ($e = $f; $e <= 3; $e++) {
                $album = new Album();
                $album  ->setName($faker->word)
                        ->setPhoto($faker->imageUrl())
                        ->addGenre($artist->getGenres()[0]);

                //Tracks loop
                $u = rand(2, 12);

                $position = 1;
                for ($v = $u; $u <= 12; $u++) {
                    $track = new Track();
                    $rand = $this->random_float(1, 4);

                    $track  ->setTitle($faker->word)
                            ->setDuration(round($rand, 2))
                            ->setAlbum($album)
                            ->setPosition($position);

                    $manager->persist($track);
                    $position++;
                }

                //Vinyl loop
                $n = rand(1, 5);
                for ($a = $n; $a < 6; $a++) {
                    $vinyl = new Vinyl();
                    $vinyl  ->setTitle($album->getName())
                            ->setReleaseDate($faker->dateTime($max = 'now', $timezone = null))
                            ->setAlbum($album)
                            ->setPhoto($faker->imageUrl())
                            ->addGenre($artist->getGenres()[0]);

                    $artist->addVinyl($vinyl);

                    $manager->persist($vinyl);
                }

                $artist->addAlbum($album);

                $manager->persist($album);
            }

            $manager->persist($artist);
        }


        $manager->flush();
    }
}
