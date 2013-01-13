<?php

namespace Komodo\StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('gender')
            ->add('year')
            ->add('class')
            ->add('level')
            ->add('address')
            ->add('zipCode')
            ->add('city')
            ->add('state')
            ->add('phone')
            ->add('motherName')
            ->add('motherLastname')
            ->add('fatherName')
            ->add('fatherLastname')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Komodo\StudentBundle\Entity\Student'
        ));
    }

    public function getName()
    {
        return 'komodo_studentbundle_studenttype';
    }
}
