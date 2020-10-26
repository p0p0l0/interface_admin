<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TypeFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {        
        $this->passwordEncoder = $passwordEncoder;;
    }

    public function load(ObjectManager $manager)
    {
       $typeWinSales = new Type();
       $typeWinSales->setName('WinSales')
                    ->setIpServer('147.135.162.109')
                    ->setUsername('thierry')
                    ->setPassword('thierrytest')
                    ->setPath('/var/www/')
                    ;
        $manager->persist($typeWinSales);

        $typeWinMam = new Type();
        $typeWinMam->setName('WinMam')
                ->setIpServer('147.135.162.108')
                ->setUsername('thierry')
                ->setPassword('thierrytest')
                ->setPath('/var/www/WinMam')
                ;
        $manager->persist($typeWinMam);

        $manager->flush();
    }
}
