<?php
namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Track;
use App\Entity\User;
use App\Entity\Vinyl;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//TODO: inserer plusieurs genres dans les artistes / albums.
//TODO: Donner des genres aux tracks.
class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
     {
         $this->passwordHasher = $passwordHasher;
     }

    public function random_float($start_number = 0, $end_number = 1, $mul = 1000000)
    {
        if ($start_number > $end_number) return false;
        return mt_rand($start_number * $mul, $end_number * $mul) / $mul;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $genres = [];

        $adminUser = new User();
        $adminUser      ->setEmail('a@a.a')
                        ->setPhoto('https://www.gojira-music.com/sites/g/files/g2000011726/f/202102/featured-music.jpg')
                        ->setFirstname('Tim')
                        ->setLastname('Messaoudene')
                        ->setPassword($this->passwordHasher->hashPassword(
                            $adminUser,
                            'aaaaaa'
                        ))
                        ->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);

        for ($i=0;$i<10;$i++){
            $user = new User();
            $user   ->setEmail($faker->email)
                    ->setPhoto('https://via.placeholder.com/450')
                    ->setFirstname($faker->firstName)
                    ->setLastname($faker->lastName)
                    ->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    $faker->password
                ));
            $manager->persist($user);
        }

        //Genre Loop
        for ($i = 0; $i <= 3; $i++){
            $genre = new Genre();
            $genre  ->setName($faker->word)
                    ->setImage('https://via.placeholder.com/450')
                    ->setDescription($faker->paragraph);
            $manager->persist($genre);
            $genres[] = $genre;
        }

        //Artiste loop
        for ($i = 1; $i <= 20; $i++) {
            $artist = new Artist;

            $r = rand(0,3);

            $artist ->setName($faker->name)
                    ->setPhoto('images/vinyls2.jpg')
                    ->addGenre($genres[$r])
                    ->setDescription($faker->paragraph);
            $r = rand(0,3);
            $artist->addGenre($genres[$r]);

            //Album loop
            $f = rand(1, 3);
            for ($e = $f; $e <= 3; $e++) {
                $album = new Album();
                $album  ->setName($faker->word)
                        ->setPhoto('https://via.placeholder.com/450')
                        ->setDescription($faker->paragraph)
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
                            ->setPrice($this->random_float(1, 200))
                            ->setPressingNumber(
                                "POS" . $faker->numberBetween(1, 10) . "H" . $faker->numberBetween(50, 150)
                            )
                            ->setReleaseDate($faker->dateTime($max = 'now', $timezone = null))
                            ->setAlbum($album)
                            ->setPhoto('https://via.placeholder.com/450')
                            ->addGenre($artist->getGenres()[0]);

                    $artist->addVinyl($vinyl);
                    if ($a % 2 == 0){
                        $user->addVinyl($vinyl);
                    }else{
                        $adminUser->addVinyl($vinyl);
                    }

                    $manager->persist($vinyl);
                    $manager->persist($user);
                    $manager->persist($adminUser);
                }

                $artist->addAlbum($album);

                $manager->persist($album);
            }

            $manager->persist($artist);
        }


        $manager->flush();
    }
}
