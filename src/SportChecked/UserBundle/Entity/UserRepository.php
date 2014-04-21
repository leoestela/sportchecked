<?php

namespace SportChecked\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {

    public function findUsersByText($userConnected, $text, $limit, $lastUser = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT u, f
		  FROM UserBundle:User u
             LEFT JOIN u.followers f WITH f.follower = :userConnected
                 WHERE u.id <> :userConnected
                   AND (u.name LIKE :text OR u.username LIKE :text OR u.intro LIKE :text)';
        
        if ($lastUser) {
            $dql = $dql . ' AND u.id < :lastUser';
        }
        
        $dql = $dql . ' ORDER BY u.id DESC';

        $query = $em->createQuery($dql);
        
        if ($userConnected) {
            $query->setParameter('userConnected', $userConnected);
        }
        
        if ($lastUser) {
            $query->setParameter('lastUser', $lastUser);
        }
        
        $query->setParameter('text', $text);

        $query->setMaxResults($limit);

        return $query->getResult();
    }
    
    public function findOptionsByUser($user) {
        $em = $this->getEntityManager();
        $dql = 'SELECT u, c, s
		  FROM UserBundle:User u
             LEFT JOIN u.contacts c WITH c.user = :user
             LEFT JOIN u.sports s WITH s.user = :user
                 WHERE u.id = :user';

        $query = $em->createQuery($dql);
        
        $query->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }    

}