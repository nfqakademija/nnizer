<?php

namespace App\Form;

use App\Entity\Contractor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', TextType::class, [
                'label' => 'registration_form.username',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => 'username.empty',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'username.short',
                        'max' => 32,
                        'maxMessage' => 'username.long',
                    ])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'registration_form.plainPassword',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'password.empty',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'password.short',
                        'max' => 4096,
                        'maxMessage' => 'password.long',
                    ]),
                ],
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
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contractor::class,
        ]);
    }
}
