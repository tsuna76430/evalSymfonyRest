<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Module;
use App\Entity\Seance;
use App\Entity\Formation;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // Formation
        for ($i = 1; $i <= 6; $i++) 
        {
            $formation = new Formation;
            $formation->setNom($faker->words(12, true));
            $formation->setDateDebut($faker->dateTimeBetween('-1 week', '+1 week'));
            $formation->setDateFin($faker->dateTimeBetween('+2 week', '+6 week'));
            $manager->persist($formation);
            $manager->flush();
            
            // Module
            for ($j = 1; $j <= mt_rand(6, 10); $j++) 
            {
                $module = new Module;
                $module->setNom($faker->words(12, true));
                $module->setNbHeures($faker->randomDigit(mt_rand(3, 100)));
                $module->setFormation($formation);
                $manager->persist($module);
                $manager->flush();

                //Seance
                for ($k = 1; $k <= mt_rand(6, 10); $k++) 
                {
                    $seance= new Seance;
                    $seance->setDateSeance($faker->dateTimeBetween('-2 week', 'now'));
                    $seance->setDuree($faker->randomFloat(1, 0.5, 4));
                    $seance->setTitre($faker->words(12, true));
                    $seance->setContenu($faker->text(120));
                    $seance->setModule($module);
                    $manager->persist($seance);
                    $manager->flush();
                }
            }

            //Utilisateur
            // $utilisateur = new Utilisateur;
            // $utilisateur->setNom($faker->words(12, true));
            // $utilisateur->setPrenom($faker->words(12, true));
            // $utilisateur->setEmail($faker->freeEmail());

            // $plainPassword = $faker->password(8, 12);
            // //$password = $this->encode->encodePassword($utilisateur, $plainPassword);
            // $utilisateur->setPassword($plainPassword);

            // $alea = mt_rand(0, 9);
            // if ($alea <= 4)
            // {
            //     $utilisateur->setRoles(array("ROLE_ETUDIANT"));
            // }
            // else
            // {
            //     $utilisateur->setRoles(array("ROLE_FORMATEUR"));
            // }
            // $manager->persist($utilisateur);

            $user = new Utilisateur();
            $user->setEmail($faker->freeEmail());
            $password = $this->encoder->encodePassword($user, $faker->password(8, 12));
            $user->setPassword($password);
            $user->setNom($faker->words(12, true));
            $user->setPrenom($faker->words(12, true));
            $user->addFormation($formation);

            $alea = mt_rand(0, 9);
            if ($alea <= 4)
            {
                $user->setRoles(array("ROLE_ETUDIANT"));
            }
            else
            {
                $user->setRoles(array("ROLE_FORMATEUR"));
            }
            $manager->persist($user);
            $manager->flush();

        }

        $user = new Utilisateur();
        $user->setEmail('theo.brentot@gmail.com');
        $password = $this->encoder->encodePassword($user, 'toto251197');
        $user->setPassword($password);
        $user->setNom('Brentot');
        $user->setPrenom('Theo');
        $user->setRoles(array("ROLE_ADMIN"));
        $manager->persist($user);
        $manager->flush();

      

    }
}
