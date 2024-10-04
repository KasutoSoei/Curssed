<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Type;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Product Title',
            ])
            ->add('image')
            ->add('description', TextareaType::class, [
            'label' => 'Product Description',
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'USD',
                'label' => 'Price',
                'constraints' => [
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'The price must be greater than 0.'
                    ]),
                    new Type([
                        'type' => ['float', 'numeric', 'integer'],
                        'message' => 'The price must be a valid number (integer or float).',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            // Submit button
            ->add('submit', SubmitType::class, [
                'label' => 'Submit', // Texte du bouton
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
