<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Vinyl;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddVinyleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('release_date')
            ->add('photo')
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label'  => 'name',
                'expanded'      => true,
            ])
            ->add('album', EntityType::class, [
                'class' => Album::class,
                'choice_label'  => 'name',
                'expanded'      => true,
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label'  => 'name',
                'multiple'      => true,
                'expanded'      => true,
                'by_reference'  => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vinyl::class,
        ]);
    }
}
