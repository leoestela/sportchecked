<?php

namespace SportChecked\CategoryBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
  public function findCategoriesMenu()
  {
	$em = $this->getEntityManager();
	$dql = 'SELECT c
		  FROM CategoryBundle:Category c
		 WHERE c.position > 0
	      ORDER BY c.position, c.name';

	$consulta = $em->createQuery($dql);
	$consulta->useResultCache(true);
        
	return $consulta->getResult();
  }
}