<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ClientRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('fk_contractor', TextType::class, [
                'label' => 'registration_form.provider',
                'data' => 'Provider 123',
                'constraints' => [
                    new NotBlank([
                        'message' => 'provider.empty',
                    ]),
                ]
            ])
            ->add('visit_date', DateTimeType::class, [
                'label' => 'registration_form.date',
                'data' => new \DateTime('now'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'date.unchosen',
                    ]),
                    new DateTime([
                        'message' => 'date.invalid',
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'registration_form.firstname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'firstname.empty',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'firstname.short',
                        'max' => 32,
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'registration_form.lastname',
                'constraints' => [
                    new NotBlank([
                        'message' => 'lastname.empty',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'lastname.short',
                        'max' => 32,
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'registration_form.email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'email.blank',
                    ]),
                    new Email([
                        'message' => 'email.invalid',
                    ])
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
