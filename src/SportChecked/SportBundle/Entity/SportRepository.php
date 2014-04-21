<?php

namespace SportChecked\SportBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SportRepository extends EntityRepository {

    public function findSportsMenu($limit) {
        $em = $this->getEntityManager();
        $dql = 'SELECT s
		  FROM SportBundle:Sport s
                 WHERE length(s.name) < 25  
	      ORDER BY s.name';

        $query = $em->createQuery($dql);
        
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findSports($userConnected, $limit, $lastSport = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT s, f
		  FROM SportBundle:Sport s
             LEFT JOIN s.followers f WITH f.user = :user ';

        if ($lastSport) {
            $dql = $dql . ' WHERE s.slug > :lastSport';
        }
        $dql = $dql . ' ORDER BY s.slug';

        $query = $em->createQuery($dql);
        
        $query->setParameter('user', $userConnected);
        if ($lastSport) {
            $query->setParameter('lastSport', $lastSport);
        }
        
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findSportsByText($text, $limit, $userConnected, $lastSport = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT s, f
                      FROM SportBundle:Sport s
                 LEFT JOIN s.followers f WITH f.user = :user
                     WHERE (s.name LIKE :text OR s.description LIKE :text)';
        if ($lastSport) {
            $dql = $dql . ' AND s.slug > :lastSport';
        }
        $dql = $dql . ' ORDER BY s.slug';

        $query = $em->createQuery($dql);

        $query->setParameter('text', $text);
        $query->setParameter('user', $userConnected);
        if ($lastSport) {
            $query->setParameter('lastSport', $lastSport);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findSportByUser($sportId, $userConnected) {
        $em = $this->getEntityManager();
        $dql = 'SELECT s, f
		  FROM SportBundle:Sport s
             LEFT JOIN s.followers f WITH f.user = :user                       
		 WHERE s.id = :sportId';

        $query = $em->createQuery($dql);
        $query->setParameter('sportId', $sportId);
        $query->setParameter('user', $userConnected);

        return $query->getOneOrNullResult();
    }

    public function findUserSportsMenu($user,$limit) {
        $em = $this->getEntityManager();
        $dql = 'SELECT partial u.{id}, partial s.{id, name}
		  FROM SportBundle:UserSport u
            INNER JOIN u.sport s
		 WHERE u.user = :user
	      ORDER BY s.slug';

        $query = $em->createQuery($dql);
        $query->setParameter('user', $user);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findUserSportsByUser($userSlug, $userConnected, $limit, $lastSportStart = null, $lastSport = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT us, u, s, f
		  FROM SportBundle:UserSport us
	    INNER JOIN us.user u 
            INNER JOIN us.sport s
             LEFT JOIN s.followers f WITH f.user = :user
                   AND f.sport = us.sport                        
		 WHERE 1 = 1';
        $dql = $dql . ' AND u.slug = :slug';
        if ($lastSportStart and $lastSport) {
            $dql = $dql . ' AND ((us.starts = :lastSportStart 
                                AND s.id < :lastSport) 
                                    OR us.starts < :lastSportStart) ';
        }
        $dql = $dql . ' ORDER BY us.starts DESC, s.id DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('slug', $userSlug);
        $query->setParameter('user', $userConnected);
        if ($lastSportStart and $lastSport) {
            $query->setParameter('lastSportStart', $lastSportStart);
            $query->setParameter('lastSport', $lastSport);
        }
        $query->setMaxResults($limit);

        return $query->getResult();
    }

}