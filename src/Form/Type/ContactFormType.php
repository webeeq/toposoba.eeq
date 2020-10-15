<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    EmailType,
    ResetType,
    SubmitType,
    TextareaType,
    TextType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('email', EmailType::class, ['label' => 'label.email'])
            ->add('subject', TextType::class, ['label' => 'label.subject'])
            ->add('message', TextareaType::class, [
                'label' => 'label.message',
                'attr' => ['style' => 'height: 220px;']
            ])
            ->add('save', SubmitType::class, ['label' => 'label.submit'])
            ->add('reset', ResetType::class, ['label' => 'label.clear'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => 'App\Form\ContactFormForm',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'contact_form_form_item'
        ]);
    }
}
