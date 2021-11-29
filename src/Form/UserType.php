<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('photo', TextType::class, [
                'attr' => [
                    'placeholder'  => 'Url de votre photo'
                ],
                'label'   => false,
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
