<?php


namespace App\Form;

use App\Entity\Tag;
use App\Entity\Walk;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalkFormType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('shortDescription', TextType::class, [
                'required' => false
            ])
            ->add('location', TextType::class, [
                'required' => false
            ] )
            ->add('distance', SemanticNumberType::class, [
                'required' => false,
                'semantic_label' => 'km'
            ])
            ->add('minimumTimeHours', SemanticIntegerType::class, [
                'required' => false,
                'semantic_label' => 'hours'
            ])
            ->add('minimumTimeMinutes', SemanticIntegerType::class, [
                'required' => false,
                'semantic_label' => 'minutes'
            ])
            ->add('ascent', SemanticIntegerType::class, [
                'required' => false,
                'semantic_label' => 'metres'
            ])
            ->add('gradient', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                ]
            ])
            ->add('paths', TextType::class, [
                'required' => false
            ])
            ->add('landscape', TextType::class, [
                'required' => false
            ])
            ->add('dogFriendliness', TextType::class, [
                'required' => false
            ])
            ->add('parking', TextType::class, [
                'required' => false
            ])
            ->add('publicToilet', TextType::class, [
                'required' => false
            ])
            ->add('suggestedMap', TextType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('tags', EntityType::class, array(
                'class' => Tag::class,
                'placeholder' => 'Add Tags'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Walk::class
        ]);
    }


}