<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Track;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'attr' => [
                    'placeholder'  => 'Titre'
                ],
                'label'   => false,
            ])
            ->add('position', IntegerType::class,[
                'attr' => [
                    'placeholder'  => 'Position'
                ],
                'label'   => false,
            ])
            ->add('duration', NumberType::class, [
                'attr' => [
                    'placeholder'  => 'Durée'
                ],
                'label'   => false,
            ])
            ->add('album', EntityType::class, [
                'class' => Album::class,
                'choice_label'  => 'name',
                'expanded'      => true,
                'label'   => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
