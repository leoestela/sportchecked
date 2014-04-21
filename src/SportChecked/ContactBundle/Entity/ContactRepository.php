<?php

namespace SportChecked\ContactBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ContactRepository extends EntityRepository {

    public function findListsMenu($user) {
        $em = $this->getEntityManager();
        $dql = 'SELECT l
		  FROM ContactBundle:ContactList l
                 WHERE l.user = :user  
                   AND l.nmembers > 0
	      ORDER BY l.name';

        $query = $em->createQuery($dql);
        $query->setParameter('user', $user);

        return $query->getResult();
    }

        public function findUserHasContacts($user) {
        $em = $this->getEntityManager();
        $dql = 'SELECT c
		  FROM ContactBundle:Contact c
		 WHERE c.user = :user
                   AND c.contact <> c.user';

        $query = $em->createQuery($dql);
        $query->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }
    
    public function findContactById($contactId) {
        $em = $this->getEntityManager();
        $dql = 'SELECT uc, c
		  FROM ContactBundle:Contact uc 
                  JOIN uc.contact c
		 WHERE 1 = 1';
        $dql = $dql . ' AND uc.id = :contactId ';

        $query = $em->createQuery($dql);
        $query->setParameter('contactId', $contactId);

        return $query->getOneOrNullResult();
    }

    public function findUserContact($userSlug, $contactSlug) {
        $em = $this->getEntityManager();
        $dql = 'SELECT uc, u, c
		  FROM ContactBundle:Contact uc
	          JOIN uc.user u 
                  JOIN uc.contact c
		 WHERE uc.user != uc.contact';
        $dql = $dql . ' AND u.slug = :userSlug ';
        $dql = $dql . ' AND c.slug = :contactSlug ';
        $dql = $dql . ' ORDER BY uc.starts DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('contactSlug', $contactSlug);
        $query->setParameter('userSlug', $userSlug);

        return $query->getOneOrNullResult();
    }

    public function findContactsByUser($userSlug, $userConnected, $limit, $lastFollowing = null, $lastContact = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT uc, u, c, f
		  FROM ContactBundle:Contact uc
	          JOIN uc.user u 
                  JOIN uc.contact c
             LEFT JOIN c.followers f WITH f.follower = :userConnected
		 WHERE uc.user != uc.contact';
        $dql = $dql . ' AND u.slug = :slug ';

        if ($lastFollowing and $lastContact) {
            $dql = $dql . ' AND ((uc.starts = :lastFollowing 
                                AND c.id < :lastContact) 
                                    OR uc.starts < :lastFollowing) ';
        }

        $dql = $dql . ' ORDER BY uc.starts DESC, c.id DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('slug', $userSlug);
        $query->setParameter('userConnected', $userConnected);

        if ($lastFollowing and $lastContact) {
            $query->setParameter('lastFollowing', $lastFollowing);
            $query->setParameter('lastContact', $lastContact);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findListsByUser($userSlug, $limit, $lastList = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT l, u
		  FROM ContactBundle:ContactList l
	          JOIN l.user u
		 WHERE 1 = 1';
        $dql = $dql . ' AND u.slug = :slug';
        if ($lastList) {
            $dql = $dql . ' AND l.id > :lastList';
        }
        $dql = $dql . ' ORDER BY l.id';

        $query = $em->createQuery($dql);
        $query->setParameter('slug', $userSlug);

        if ($lastList) {
            $query->setParameter('lastList', $lastList);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function findListMembersByUser($userSlug, $contactId) {
        $em = $this->getEntityManager();
        $dql = 'SELECT l, u, m
		  FROM ContactBundle:ContactList l
	    INNER JOIN l.user u
             LEFT JOIN l.members m WITH m.contact = :contactId AND m.contactlist = l.id
		 WHERE 1 = 1';
        $dql = $dql . ' AND u.slug = :slug';
        $dql = $dql . ' ORDER BY l.name';

        $query = $em->createQuery($dql);
        $query->setParameter('slug', $userSlug);
        $query->setParameter('contactId', $contactId);
        $query->setMaxResults(25);

        return $query->getResult();
    }

    public function findListMembersByList ($listId, $limit, $lastListed = null, $lastMember = null) {
        $em = $this->getEntityManager();
        $dql = 'SELECT m, l, c, u, uc, fs
		  FROM ContactBundle:ListMember m
	    INNER JOIN m.contactlist l
            INNER JOIN m.contact c
            INNER JOIN l.user u
            INNER JOIN c.contact uc
             LEFT JOIN uc.followers fs WITH fs.follower = u.id
		 WHERE 1 = 1';
        $dql = $dql . ' AND m.contactlist = :listId';

        if ($lastListed and $lastMember) {
            $dql = $dql . ' AND ((m.starts = :lastListed 
                                AND c.id < :lastMember) 
                                    OR m.starts < :lastListed) ';
        }

        $dql = $dql . ' ORDER BY m.starts DESC, uc.id DESC';

        $query = $em->createQuery($dql);
        $query->setParameter('listId', $listId);

        if ($lastListed and $lastMember) {
            $query->setParameter('lastListed', $lastListed);
            $query->setParameter('lastMember', $lastMember);
        }

        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function deleteListMembers($list) {
        $em = $this->getEntityManager();
        $dql = 'DELETE ContactBundle:ListMember lm
		 WHERE lm.contactlist = :list';

        $delete = $em->createQuery($dql);
        $delete->setParameter('list', $list);

        return $delete->execute();
    }     
}