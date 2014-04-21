<?php

namespace SportChecked\FeatureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SportChecked\FeatureBundle\Entity\Feature;

class genders extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 20;
    }

    public function load(ObjectManager $manager) {
        $features = array(
            array('id'=> 'female', 'type'=> 1, 'name' => 'Mujeres', 'position' => 1),
            array('id'=> 'male', 'type'=> 1, 'name' => 'Hombres', 'position' => 2),
            array('id'=> 'mixed', 'type'=> 1, 'name' => 'Mixto', 'position' => 3),
            array('id'=> 'audio', 'type'=> 2, 'name' => 'Audio', 'position' => 1),
            array('id'=> 'video', 'type'=> 2, 'name' => 'Video', 'position' => 2),
            array('id'=> 'male3', 'type'=> 3, 'name' => 'Hombre', 'position' => 1),
            array('id'=> 'female3', 'type'=> 3, 'name' => 'Mujer', 'position' => 2), 
            array('id'=> 'nogender', 'type'=> 3, 'name' => 'Sin especificar', 'position' => 3), 
        );

        foreach ($features as $feature) {
            $entity = new Feature();
            $entity->setId($feature['id']);
            $entity->setType($feature['type']);
            $entity->setName($feature['name']);
            $entity->setPosition($feature['position']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

}

