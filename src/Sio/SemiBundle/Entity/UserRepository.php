<?php 

namespace Sio\SemiBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($mail)
    {
        $q = $this
            ->createQueryBuilder('user')
            ->where('user.mail = :mail')
            ->setParameter('mail', $mail)
            ->getQuery();

        try
        {
            $user = $q->getSingleResult();
        }
        catch (NoResultException $e)
        {
            throw new UsernameNotFoundException(sprintf('Impossible de trouver un utilisateur avec l\'E-mail : "%s".', $mail), 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
}