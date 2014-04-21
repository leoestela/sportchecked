<?php

namespace SportChecked\PublicationBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PublicationSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('text', 'text', array('required' => false))
        ;
    }

    public function getName() {
        return 'sportchecked_publicationbundle_publicationsearchtype';
    }

}
