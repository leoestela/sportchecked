<?php

namespace SportChecked\CategoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SportChecked\CategoryBundle\Entity\Category;

class categories extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 10;
    }

    public function load(ObjectManager $manager) {
        $categories = array(
            array('name' => 'Artículos', 'position' => '1'),
            array('name' => 'Entrevistas', 'position' => '2'),
            array('name' => 'Videos', 'position' => '3'),
            array('name' => 'Audios', 'position' => '4'),
            array('name' => 'Películas', 'position' => '5'),
            array('name' => 'Libros', 'position' => '6'),            
            array('name' => 'Entrenamientos', 'position' => '7'),
            array('name' => 'Material deportivo', 'position' => '8'),
            array('name' => 'Lugares', 'position' => '9'),
            array('name' => 'Instalaciones', 'position' => '10'),
            array('name' => 'Rutas', 'position' => '11'),
            array('name' => 'Eventos', 'position' => '12'),
            array('name' => 'Juegos', 'position' => '13'),
            array('name' => 'Apuestas', 'position' => '14'),
        );

        foreach ($categories as $category) {
            $entity = new Category();
            $entity->setName($category['name']);
            $entity->setPosition($category['position']);
            $manager->persist($entity);
        }
        $manager->flush();
    }

}

