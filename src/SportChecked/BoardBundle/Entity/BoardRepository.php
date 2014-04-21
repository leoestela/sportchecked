<?php

namespace SportChecked\BoardBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BoardRepository extends EntityRepository {

    public function findBoardsByUserSlug($userSlug, $limit, $lastBoard = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT b, c, u
		  FROM BoardBundle:Board b
             LEFT JOIN b.category c
	    INNER JOIN b.user u
		 WHERE 1 = 1';
        $dql = $dql . ' AND u.slug = :slug';
        if ($lastBoard) {
            $dql = $dql . ' AND b.id > :lastBoard';
        }
        $dql = $dql . ' ORDER BY b.id';

        $query = $em->createQuery($dql);
        $query->setParameter('slug', $userSlug);
        
        if ($lastBoard) {
        $query->setParameter('lastBoard', $lastBoard);
        }
        
        $query->setMaxResults($limit);

        return $query->getResult();
    }

}
