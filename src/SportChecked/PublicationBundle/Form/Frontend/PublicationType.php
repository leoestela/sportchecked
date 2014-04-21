<?php

namespace SportChecked\PublicationBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PublicationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                //->add('sport', 'entity', array('class' => 'SportBundle:Sport','property' => 'name'))
                ->add('sport', 'entity', array(
                    'class' => 'SportBundle:Sport',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('s')
                                ->where('EXISTS (SELECT 1 FROM SportBundle:UserSport us WHERE us.sport = s.id and us.user = :param)')
                                ->setParameter('param', $options['attr']['user'])
                                ->orderBy('s.name');
                    },
                    'empty_value' => 'Selecciona un deporte',
                    'required' => true
                        )
                )
                ->add('modality', 'entity', array('class' => 'FeatureBundle:Feature',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                                ->where('m.type = 1')
                                ->orderBy('m.position');
                    },
                    'empty_value' => 'Cualquier modalidad'
                    , 'required' => false))
                ->add('board', 'entity', array(
                    'class' => 'BoardBundle:Board',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('b')
                                ->where('b.user = :param')
                                ->setParameter('param', $options['attr']['user'])
                                ->orderBy('b.name');
                    },
                    'empty_value' => 'Selecciona una carpeta',
                    'required' => true
                        )
                )
                ->add('title', 'textarea', array('required' => true, 'attr' => array('placeholder' => 'Titular de la publicación')))
                ->add('subtitle', 'textarea', array('required' => false, 'attr' => array('placeholder' => 'Puedes añadir un subtítulo')))
                ->add('file', null, array('required' => false, 'attr' => array('class' => 'file input-file', 'id' => 'file1')))
                ->add('body', 'textarea', array('required' => false, 'attr' => array('placeholder' => 'Añade el texto de la publicación')))
                ->add('link', null, array('required' => false, 'attr' => array('placeholder' => 'http://')))
        ;
    }

    public function getName() {
        return 'sportchecked_publicationbundle_publicationtype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SportChecked\PublicationBundle\Entity\Publication',
        ));
    }

}
