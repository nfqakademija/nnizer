<?php


namespace App\Form;

use App\Entity\Contractor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ContractorDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('coverPhoto', VichImageType::class, [
                'label' => 'detailsForm.coverPhoto',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
            ])
            ->add('profilePhoto', VichImageType::class, [
                'label' => 'detailsForm.profilePhoto',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contractor::class,
        ]);
    }

}