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
       $typeWinSales->setName('TypeTest1')
                    ->setIpServer('147.123.123.123')
                    ->setUsername('interface')
                    ->setPassword('password')
                    ->setPath('/home/interface/www/')
                    ->setServerName('typetest1.org')
                    ;
        $manager->persist($typeWinSales);

        $typeWinMam = new Type();
        $typeWinMam->setName('TypeTest2')
                ->setIpServer('147.131.131.131')
                ->setUsername('interface')
                ->setPassword('password')
                ->setPath('/var/www/typetest2')
                ->setServerName('typetest2.cloud')
                ;
        $manager->persist($typeWinMam);

        $manager->flush();
    }
}
