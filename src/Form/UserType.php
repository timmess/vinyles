<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder'  => 'Adresse mail'
                ],
                'label'   => false,
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder'  => 'Prénom'
                ],
                'label'   => false,
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder'  => 'Nom'
                ],
                'label'   => false,
            ])
            ->add('photo', FileType::class, [
                'label'   => "Photo de profil",
                'attr' => [
                    'placeholder'  => 'Url de votre photo'
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image dans son bon format (JPEG, PNG, SVG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
