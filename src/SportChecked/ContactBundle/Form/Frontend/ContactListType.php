<?php

namespace SportChecked\ContactBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactListType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'textarea', array('required' => true, 'attr' => array('placeholder' => 'Nombre de la lista')))
                ->add('description', 'textarea', array('required' => false, 'attr' => array('placeholder' => 'Puedes introducir una descripción')))
                ->add('file', null, array('required' => false, 'attr' => array('class' => 'file input-file', 'id' => 'file1')))
        ;
    }

    public function getName() {
        return 'sportchecked_contactbundle_contactlisttype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\ContactBundle\Entity\ContactList',
        ));
    }

}