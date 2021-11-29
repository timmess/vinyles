<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'attr' => [
                    'placeholder'  => 'Nom'
                ],
                'label'   => false,
            ])
            ->add('description',TextareaType::class, [
                'attr' => [
                    'placeholder'  => 'Description'
                ],
                'label'   => false,
            ])
            ->add('photo',TextType::class, [
                'attr' => [
                    'placeholder'  => 'Url de la photo'
                ],
                'label'   => false,
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label'  => 'name',
                'multiple'      => true,
                'expanded'      => true,
                'by_reference'  => false,
                'label'   => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
