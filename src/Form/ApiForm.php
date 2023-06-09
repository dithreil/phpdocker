<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiForm extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'allow_extra_fields' => true,
            ],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}