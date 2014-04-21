<?php

namespace SportChecked\MessageBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('subject', 'textarea', array('required' => true, 'attr' => array('placeholder' => 'Asunto')))
                ->add('description', 'textarea', array('required' => false, 'attr' => array('placeholder' => 'Descripción')))
        ;
    }

    public function getName() {
        return 'sportchecked_messagebundle_messagetype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\MessageBundle\Entity\Message',
        ));
    }

}