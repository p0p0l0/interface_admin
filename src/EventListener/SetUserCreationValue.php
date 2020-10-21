<?php

namespace App\EventListener;

use App\Entity\Customer;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SetUserCreationValue
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity =$args->getEntity();
        if(!$entity instanceof Customer){
            return;
        }

        $entity->setUserCreation($this->tokenStorage->getToken()->getUser()->getUsername());
    }

    
}