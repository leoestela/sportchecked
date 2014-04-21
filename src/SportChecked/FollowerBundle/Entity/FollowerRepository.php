<?php

namespace SportChecked\FollowerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FollowerRepository extends EntityRepository {

    public function findFollowersByUser($userSlug, $userConnected, $limit, $lastFollow = null, $lastFollowed = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT uf, u, f, fs
		  FROM FollowerBundle:Follower uf
	    INNER JOIN uf.user u 
            INNER JOIN uf.follower f
             LEFT JOIN f.followers fs WITH fs.follower = :userConnected
		 WHERE uf.follower <> uf.user';
        $dql = $dql . ' AND u.slug = :slug';
        if ($lastFollow and $lastFollowed) {
            $dql = $dql . ' AND ((uf.starts = :lastFollow 
                                AND f.id < :lastFollowed) 
                                    OR uf.starts < :lastFollow) ';
        }
        $dql = $dql . ' ORDER BY uf.starts DESC, f.id DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('slug', $userSlug);
        $query->setParameter('userConnected', $userConnected);
        if ($lastFollow and $lastFollowed) {
            $query->setParameter('lastFollow', $lastFollow);
            $query->setParameter('lastFollowed', $lastFollowed);
        }
        $query->setMaxResults($limit);

        return $query->getResult();
    }

}
