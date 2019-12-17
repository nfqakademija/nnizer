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
        $pattern = '/(([0-1][0-9]|2[0-4]):[0-5][0-9] \- ([0-1][0-9]|2[0-4]):[0-5][0-9])|(\-1)/';
        $builder
            ->add('Monday', TextType::class, [
                'label' => 'settings_form.Monday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ]),
                ]
            ])
            ->add('Tuesday', TextType::class, [
                'label' => 'settings_form.Tuesday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ])
                ]
            ])
            ->add('Wednesday', TextType::class, [
                'label' => 'settings_form.Wednesday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ])
                ]
            ])
            ->add('Thursday', TextType::class, [
                'label' => 'settings_form.Thursday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ])
                ]
            ])
            ->add('Friday', TextType::class, [
                'label' => 'settings_form.Friday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ])
                ]
            ])
            ->add('Saturday', TextType::class, [
                'label' => 'settings_form.Saturday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ])
                ]
            ])
            ->add('Sunday', TextType::class, [
                'label' => 'settings_form.Sunday',
                'attr' => [
                    'placeholder' => '07:00 - 17:00',
                ],
                'empty_data' => '-1',
                'constraints' => [
                    new Regex([
                        'message' => 'settings.regex',
                        'pattern' => $pattern,
                    ])
                ]
            ])
            ->add('visitDuration', NumberType::class, [
                'attr' => [
                    'placeholder' => '20',
                ],
                'label' => 'settings_form.visitDuration',
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
