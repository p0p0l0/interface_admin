<?php
// Fixtures pour implementer des users ds ma base
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {        
        $this->passwordEncoder = $passwordEncoder;;
    }
    public function load(ObjectManager $manager)
    {
        $user= new User();
        $user->setUsername('thierry')
             ->setPassword($this->passwordEncoder->encodePassword(
                             $user,
                             'testtest'
                         ));
        $manager->persist($user);
        

        $admin = new User();
        $admin->setUsername('admin')
             ->setPassword($this->passwordEncoder->encodePassword(
                             $admin,
                             'admin'
                         ));
        $manager->persist($admin);
        $manager->flush();

    }
}
