<?php

namespace SportChecked\UserBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UserProfileType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'text')
                ->add('email', 'email')
                ->add('username', 'text')
                ->add('intro', 'textarea', array('required' => false))
                ->add('ubication', 'text', array('required' => false))
                ->add('web', 'text', array('required' => false))
                ->add('gender', 'entity', array('class' => 'FeatureBundle:Feature',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                                ->where('g.type = 3')
                                ->orderBy('g.position');
                    }, 'required' => false, 'multiple' => false, 'expanded' => true))
                ->add('country', 'entity', array('class' => 'FeatureBundle:Country',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy('c.name');
                    }, 'required' => false))
                ->add('file', null, array('required' => false, 'attr' => array('class' => 'file input-file', 'id' => 'file1')));
    }

    public function getName() {
        return 'sportchecked_userbundle_userprofiletype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\UserBundle\Entity\User',
        ));
    }

}
