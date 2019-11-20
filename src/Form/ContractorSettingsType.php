<?php

namespace App\Form;

use App\Entity\ContractorSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContractorSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Monday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => '([0-1][0-9]|2[0-4]):[0-5][0-9] - ([0-1][0-9]|2[0-4]):[0-5][0-9]',
                    ])
                ]
            ])
            ->add('Tuesday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => '([0-1][0-9]|2[0-4]):[0-5][0-9] - ([0-1][0-9]|2[0-4]):[0-5][0-9]',
                    ])
                ]
            ])
            ->add('Wednesday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new NotBlank([
                        'message' => 'provider.empty',
                    ]),
                ]
            ])
            ->add('Thursday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => '([0-1][0-9]|2[0-4]):[0-5][0-9] - ([0-1][0-9]|2[0-4]):[0-5][0-9]',
                    ])
                ]
            ])
            ->add('Friday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new NotBlank([
                        'message' => 'provider.empty',
                    ]),
                ]
            ])
            ->add('Saturday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => '([0-1][0-9]|2[0-4]):[0-5][0-9] - ([0-1][0-9]|2[0-4]):[0-5][0-9]',
                    ])
                ]
            ])
            ->add('Sunday', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => '([0-1][0-9]|2[0-4]):[0-5][0-9] - ([0-1][0-9]|2[0-4]):[0-5][0-9]',
                    ])
                ]
            ])
            ->add('visitDuration', NumberType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new NotBlank([
                        'message' => 'settings.empty',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContractorSettings::class,
        ]);
    }
}
