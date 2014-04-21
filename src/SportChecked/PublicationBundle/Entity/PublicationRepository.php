<?php

namespace SportChecked\PublicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PublicationRepository extends EntityRepository {

    public function findPublicationsIndex
    ($sport, $modality, $category, $limit, $lastAction = null, $lastPublication = null, $text = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT partial pa.{id, lastaction}, 
                       p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, partial m.{id, name}
		  FROM PublicationBundle:PublicationAction pa
	         INNER JOIN pa.publication p
	         INNER JOIN pa.user u
	         INNER JOIN p.sport s
                  LEFT JOIN p.modality m
		 WHERE (pa.published IS NOT NULL OR pa.saved IS NOT NULL OR pa.shared IS NOT NULL)';

        if ($sport) {
            $dql = $dql . ' AND p.sport = :sport';
        }
        if ($modality) {
            $dql = $dql . ' AND p.modality = :modality';
        }
        if ($category) {
            $dql = $dql . ' AND pa.category = :category';
        }
        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        if ($text) {
            $dql = $dql . ' AND p.body like :text';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        if ($sport) {
            $query->setParameter('sport', $sport);
        }
        if ($modality) {
            $query->setParameter('modality', $modality);
        }
        if ($category) {
            $query->setParameter('category', $category);
        }
        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }
        if ($text) {
            $query->setParameter('text', $text);
        }
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findPublicationsIndexByUser
    ($user, $sport, $modality, $category, $list, $limit, $lastAction = null, $lastPublication = null, $text = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT 
                       pa, 
                       p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, 
                       pas,
                       partial m.{id, name}';
        $dql = $dql .
                ' FROM PublicationBundle:PublicationAction pa
	         INNER JOIN pa.publication p
                 INNER JOIN pa.user u
	         INNER JOIN p.sport s	
                  LEFT JOIN p.modality m
                  LEFT JOIN p.actions pas WITH pas.user = :user';
        if ($sport == 'my') {
            $dql = $dql .
                    ' INNER JOIN s.followers sf WITH sf.user = :user';
        }
        if ($list == 'my') {
            $dql = $dql .
                    ' INNER JOIN u.followers f WITH f.follower = :user';
        }
        $dql = $dql .
                ' WHERE ((pa.user = :user AND pa.published IS NOT NULL AND pa.shared IS NULL AND pa.saved IS NULL)
                       OR pa.user <> :user)
                       AND (pa.published IS NOT NULL OR pa.saved IS NOT NULL OR pa.shared IS NOT NULL) ';

        if ($sport AND $sport != 'my') {
            $dql = $dql .
                    ' AND p.sport = :sport';
        }
        if ($list AND $list != 'my') {
            $dql = $dql . ' AND EXISTS (SELECT 1 FROM ContactBundle:Contact c 
                                           INNER JOIN c.lists l WITH l.contactlist = :list AND l.contact = c.id
                            WHERE c.user = :user AND c.contact = pa.user) ';
        }
        if ($modality) {
            $dql = $dql . ' AND p.modality = :modality';
        }
        if ($category) {
            $dql = $dql . ' AND pa.category = :category';
        }

        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        if ($text) {
            $dql = $dql . ' AND p.body like :text';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        $query->setParameter('user', $user);

        if ($sport AND $sport != 'my') {
            $query->setParameter('sport', $sport);
        }
        if ($list AND $list != 'my') {
            $query->setParameter('list', $list);
        }
        if ($modality) {
            $query->setParameter('modality', $modality);
        }
        if ($category) {
            $query->setParameter('category', $category);
        }
        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }

        if ($text) {
            $query->setParameter('text', $text);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findPublicationsByUserAndText
    ($user, $text, $limit, $lastAction = null, $lastPublication = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT DISTINCT 
                       pa, 
                       p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, 
                       pas,
                       partial m.{id, name}';
        $dql = $dql .
                ' FROM PublicationBundle:PublicationAction pa
	         INNER JOIN pa.publication p
                 INNER JOIN pa.user u
	         INNER JOIN p.sport s	
                  LEFT JOIN p.modality m
                  LEFT JOIN p.actions pas WITH pas.user = :user';
        $dql = $dql .
                ' WHERE ((pa.user = :user AND pa.published IS NOT NULL AND pa.shared IS NULL AND pa.saved IS NULL)
                       OR pa.user <> :user)';

        $dql = $dql . ' AND (p.title like :text OR p.subtitle like :text OR p.body like :text)
                        AND (pa.published IS NOT NULL OR pa.saved IS NOT NULL OR pa.shared IS NOT NULL)';

        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        $query->setParameter('user', $user);
        $query->setParameter('text', $text);

        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findPublicationsByText
    ($text, $limit, $lastAction = null, $lastPublication = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT partial pa.{id, lastaction}, 
                       p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, partial m.{id, name}
		  FROM PublicationBundle:PublicationAction pa
	         INNER JOIN pa.publication p
	         INNER JOIN pa.user u
	         INNER JOIN p.sport s
                  LEFT JOIN p.modality m
		 WHERE (p.title like :text OR p.subtitle like :text OR p.body like :text)';

        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }
        $query->setParameter('text', $text);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findPublicationById($id) {
        $em = $this->getEntityManager();

        $dql = 'SELECT p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug},
                       partial m.{id, name}
		  FROM PublicationBundle:Publication p
	         INNER JOIN p.user u
	         INNER JOIN p.sport s	
                  LEFT JOIN p.modality m
		 WHERE p.id = :id';

        $query = $em->createQuery($dql);

        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    public function findPublicationByUser($id, $user) {
        $em = $this->getEntityManager();

        $dql = 'SELECT p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, 
                       pas,
                       partial m.{id, name}
		  FROM PublicationBundle:Publication p
	         INNER JOIN p.user u
	         INNER JOIN p.sport s
                  LEFT JOIN p.modality m
                  LEFT JOIN p.actions pas WITH pas.user = :user
		 WHERE p.id = :id';

        $query = $em->createQuery($dql);

        $query->setParameter('id', $id);
        $query->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    public function findPublicationWithComments($id, $user) {
        $em = $this->getEntityManager();

        $dql = 'SELECT p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, 
                       c,
                       pas,
                       partial m.{id, name}
		  FROM PublicationBundle:Publication p
	         INNER JOIN p.user u
	         INNER JOIN p.sport s
                  LEFT JOIN p.modality m
                  LEFT JOIN p.comments c
                  LEFT JOIN p.actions pas WITH pas.user = :user
		 WHERE p.id = :id';

        $dql = $dql . ' ORDER BY c.created DESC, c.id DESC';

        $query = $em->createQuery($dql);

        $query->setParameter('id', $id);
        $query->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

    public function findPublicationsByUser
    ($user, $userConnected, $limit, $lastAction = null, $lastPublication = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT pa,
                       p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, 
                       pas,
                       partial m.{id, name}
		  FROM PublicationBundle:PublicationAction pa
                 INNER JOIN pa.user ua
	         INNER JOIN pa.publication p
	         INNER JOIN p.user u
	         INNER JOIN p.sport s	 
                  LEFT JOIN p.modality m
                  LEFT JOIN p.actions pas WITH pas.user = :userConnected 
                        AND pas.publication = pa.publication
		 WHERE pa.user = :user 
                   AND (pa.published IS NOT NULL 
                        OR pa.shared IS NOT NULL
                        OR pa.saved IS NOT NULL)';

        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        $query->setParameter('user', $user);
        $query->setParameter('userConnected', $userConnected);

        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findPublicationsByBoard
    ($board, $userConnected, $limit, $lastAction = null, $lastPublication = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT pa,
                       p, 
                       u, 
                       b,
                       partial s.{id, name, slug}, 
                       pas,
                       partial m.{id, name}
		  FROM PublicationBundle:PublicationAction pa
                 INNER JOIN pa.user ua
                 INNER JOIN pa.board b
	         INNER JOIN pa.publication p
	         INNER JOIN p.user u
	         INNER JOIN p.sport s	
                  LEFT JOIN p.modality m
                  LEFT JOIN p.actions pas WITH pas.user = :userConnected 
                        AND pas.publication = pa.publication
		 WHERE b.user = pa.user
                   AND pa.board = :board ';

        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        $query->setParameter('board', $board);
        $query->setParameter('userConnected', $userConnected);

        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function deletePublicationBoard($boardId) {
        $em = $this->getEntityManager();
        $dql = 'UPDATE PublicationBundle:Publication p
                   SET p.board = NULL
		 WHERE p.board = :boardId';

        $update = $em->createQuery($dql);
        $update->setParameter('boardId', $boardId);

        return $update->execute();
    }    
    
    public function deletePublicationActionBoard($boardId) {
        $em = $this->getEntityManager();
        $dql = 'UPDATE PublicationBundle:PublicationAction pa
                   SET pa.published = NULL, pa.saved = NULL, pa.board = NULL
		 WHERE pa.board = :boardId';

        $update = $em->createQuery($dql);
        $update->setParameter('boardId', $boardId);

        return $update->execute();
    }
    
    public function updatePublicationActionCategory($boardId, $categoryId) {
        $em = $this->getEntityManager();
        $dql = 'UPDATE PublicationBundle:PublicationAction pa
                   SET pa.category = :categoryId
		 WHERE pa.board = :boardId
                   AND (pa.category <> :categoryId OR pa.category IS NULL)';

        $update = $em->createQuery($dql);
        $update->setParameter('boardId', $boardId);
        $update->setParameter('categoryId', $categoryId);

        return $update->execute();
    }    

    public function findPublicationsBySport
    ($sport, $modality, $userConnected, $limit, $lastAction = null, $lastPublication = null) {
        $em = $this->getEntityManager();

        $dql = 'SELECT pa,
                       p, 
                       partial u.{id, username, name, photo, slug}, 
                       partial s.{id, name, slug}, 
                       pas,
                       partial m.{id, name}
		  FROM PublicationBundle:PublicationAction pa
                 INNER JOIN pa.user ua
	         INNER JOIN pa.publication p
	         INNER JOIN p.user u
	         INNER JOIN p.sport s	 
                  LEFT JOIN p.modality m
                  LEFT JOIN p.actions pas WITH pas.user = :userConnected 
                        AND pas.publication = pa.publication
		 WHERE p.sport = :sport 
                   AND pa.published IS NOT NULL';

        if ($modality) {
            $dql = $dql . ' AND p.modality = :modality ';
        }

        if ($lastAction and $lastPublication) {
            $dql = $dql . ' AND ((pa.lastaction = :lastAction 
                                AND p.id < :lastPublication) 
                                    OR pa.lastaction < :lastAction) ';
        }

        $dql = $dql . ' ORDER BY pa.lastaction DESC, p.id DESC';

        $query = $em->createQuery($dql);

        $query->setParameter('sport', $sport);
        $query->setParameter('userConnected', $userConnected);

        if ($modality) {
            $query->setParameter('modality', $modality);
        }

        if ($lastAction and $lastPublication) {
            $query->setParameter('lastAction', $lastAction);
            $query->setParameter('lastPublication', $lastPublication);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

}