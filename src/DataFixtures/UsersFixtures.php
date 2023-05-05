<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{       
    public function __construct( private UserPasswordHasherInterface $passwordEncoder ,
                                 private SluggerInterface $sluger ){}

    public function load(ObjectManager $manager): void
    {   
        $admin = new Users();
        $admin->setEmail('admin@demo.fr');
        $admin->setFirstname('Haitame');
        $admin->setLastname('Laframe');
        $admin->setAddress('Route de Rabat');
        $admin->setCodepostal('90000');
        $admin->setCity('Tanger');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin,'admin')
        );
        // $product = new Product();
        $manager->persist($admin);


        $faker=Faker\Factory::create('fr_FR');
        for($usr=1;$usr<=5;$usr++){
                $user = new Users();
                $user->setEmail($faker->email());
                $user->setFirstname($faker->lastName);
                $user->setLastname($faker->firstName);
                $user->setAddress($faker->streetAddress);
                $user->setCodepostal(str_replace(' ','',$faker->postcode));
                $user->setCity($faker->city);
                
                $user->setPassword(
                    $this->passwordEncoder->hashPassword($user,'secret')
                );
                // $product = new Product();
                $manager->persist($user);

        }
        $manager->flush();
    }
}
