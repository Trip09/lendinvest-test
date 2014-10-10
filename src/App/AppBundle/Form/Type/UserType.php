<?php

namespace App\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                'text',
                array(
                    'required' => true,
                    'disabled' => !empty($options['data']),
                )
            )
            ->add(
                'dateOfBirth',
                'date',
                array(
                    'required' => true,
                    'input' => 'datetime',
                    'widget' => 'single_text',
                    'format' => 'dd.MM.yyyy.',
                    'label' => 'Date of birth (dd.mm.yyyy.)',
                )
            )
            ->add(
                'accountNumber',
                'text',
                array(
                    'required' => true,
                )
            )
            ->add(
                'Save',
                'submit',
                array(
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\\AppBundle\\Entity\\User'
        ));
    }
}