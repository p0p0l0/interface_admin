<?php

namespace App\EventListener;

use App\Entity\Customer;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SetUserEditValue
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function preUpdate(LifecycleEventArgs $args){
        
        $entity = $args->getEntity();

        
        if (!$entity instanceof Customer) {
            return;
        }

        $entity->setUserEdit($this->tokenStorage->getToken()->getUser()->getUsername());
    }
}