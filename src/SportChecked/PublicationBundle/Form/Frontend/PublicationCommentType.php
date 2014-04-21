<?php

namespace SportChecked\PublicationBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PublicationCommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('comment', 'textarea', array('required' => false, 'attr' => array('class' => 'textarea-s')))
        ;
    }

    public function getName() {
        return 'sportchecked_publicationbundle_publicationcommenttype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\PublicationBundle\Entity\PublicationComment',
        ));
    }

}
