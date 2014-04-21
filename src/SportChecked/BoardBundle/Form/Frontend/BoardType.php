<?php

namespace SportChecked\BoardBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BoardType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'textarea', array('required' => true, 'attr' => array('placeholder' => 'Nombre de la carpeta')))
                ->add('description', 'textarea', array('required' => false, 'attr' => array('placeholder' => 'Puedes introducir una descripción')))
                ->add('category', 'entity', array(
                    'class' => 'CategoryBundle:Category', 'property' => 'name', 'empty_value' => 'Sin categoría', 'required' => false))
                ->add('file', null, array('required' => false, 'attr' => array('class' => 'file input-file', 'id' => 'file1')))
        ;
    }

    public function getName() {
        return 'sportchecked_boardbundle_boardtype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\BoardBundle\Entity\Board',
        ));
    }

}