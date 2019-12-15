<?php

namespace App\Form;

use App\Entity\Contractor;
use App\Entity\ServiceType;
use App\Repository\ServiceTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContractorDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('services', EntityType::class, [
                'label' => 'detailsForm.service.type',
                'class' => ServiceType::class,
                'choice_label' => 'name',
                'choice_translation_domain' => true,
            ])
            ->add('title', TextType::class, [
                'label' => 'detailsForm.service.title',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'detailsForm.service.placeholder',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'detailsForm.service.empty',
                    ]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'detailsForm.description.title',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'detailsForm.description.placeholder',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'detailsForm.description.empty',
                    ]),
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'detailsForm.address.title',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'detailsForm.address.placeholder',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'detailsForm.address.empty',
                    ]),
                ]
            ])
            ->add('facebook', TextType::class, [
                'label' => 'detailsForm.facebook.title',
                'required' => false,
                'attr' => [
                    'placeholder' => 'detailsForm.facebook.placeholder',
                ],
            ])
            ->add('coverPhoto', CoverPhotoType::class, [
                'label' => 'detailsForm.profilePhoto',
            ])
            ->add('profilePhoto', ProfilePhotoType::class, [
                'label' => 'detailsForm.profilePhoto',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contractor::class,
        ]);
    }

    /**
     * @param ServiceTypeRepository $services
     * @return ServiceType[]
     */
    private function getServiceTypes(ServiceTypeRepository $services): array
    {
        return $services->findAll();
    }
}
