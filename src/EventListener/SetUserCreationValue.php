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

        if (!$entity instanceof Customer) {
            return;
        }

        if (null !== $currentUser = $this->getUser()) {
            $entity->setUserCreation($currentUser->getUsername());
        } else {
            $entity->setUserCreation(0);
        }
    }

    public function getUser()
    {
        if (!$this->tokenStorage) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    
}
    
