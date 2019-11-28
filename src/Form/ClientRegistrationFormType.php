<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\DataTransformer\ContractorToObjectTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ClientRegistrationFormType extends AbstractType
{
    /**
     * @var ContractorToObjectTransformer
     */
    private $transformer;

    /**
     * ClientRegistrationFormType constructor.
     * @param ContractorToObjectTransformer $transformer
     */
    public function __construct(ContractorToObjectTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('contractor', TextType::class, [
                'label' => 'registration_form.provider',
                'constraints' => [
                    new NotBlank([
                        'message' => 'provider.empty',
                    ]),
                ]
            ])
            ->add('visitDate', DateTimeType::class, [
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
                        'maxMessage' => 'firstname.long',
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
                        'maxMessage' => 'lastname.long',
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
            ]);

        $builder->get('contractor')
            ->addModelTransformer($this->transformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
