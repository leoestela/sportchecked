<?php

namespace SportChecked\BoardBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BoardSimpleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'text', array('required' => false, 'label' => 'Crear nueva carpeta'))
        ;
    }

    public function getName() {
        return 'sportchecked_boardbundle_boardsimpletype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\BoardBundle\Entity\Board',
        ));
    }

}