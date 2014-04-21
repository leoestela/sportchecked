<?php

namespace SportChecked\FeatureBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FeatureRepository extends EntityRepository
{
  public function findModalitiesMenu()
  {
	$em = $this->getEntityManager();
	$dql = 'SELECT f
		  FROM FeatureBundle:Feature f
                 WHERE f.type = 1  
	      ORDER BY f.position';

	$consulta = $em->createQuery($dql);
        $consulta->useResultCache(true);
	
	return $consulta->getResult();
  }
}
