<?php

namespace SportChecked\SportBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SportSearchType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('text', 'text', array('required' => false))
        ;
    }

    public function getName() {
        return 'sportchecked_sportbundle_sportsearchtype';
    }

}
