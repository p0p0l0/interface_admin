<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TypeFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
       $typeWinSales = new Type();
       $typeWinSales->setName('WinSales')
                    ->setIpServer('147.135.162.109')
                    ->setUsername('interface')
                    ->setPassword('9w4hZ9Ke7D')
                    ->setPath('/home/interface/www/')
                    ->setServerName('winsales.biz')
                    ;
        $manager->persist($typeWinSales);

        $typeWinMam = new Type();
        $typeWinMam->setName('WinMam')
                ->setIpServer('147.135.162.108')
                ->setUsername('interface')
                ->setPassword('9w4hZ9Ke7D')
                ->setPath('/var/www/WinMam')
                ->setServerName('winmam.cloud')
                ;
        $manager->persist($typeWinMam);

        $manager->flush();
    }
}
