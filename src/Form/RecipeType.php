<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class RecipeType
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class RecipeType extends AbstractType
{

    /** @var TokenStorageInterface  */
    private $token;

    /**
     * This is the constructor of the class RecipeType
     *
     * @param TokenStorageInterface $token
     */
    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    /**
     * This method is used to build the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => '50']),
                    new Assert\NotBlank()
                ]
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440
                ],
                'label' => 'Temps (en minutes)',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 50
                ],
                'label' => 'Nombre des personnes',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(5)
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix ',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\NotNull(),
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('ingredients', EntityType::class, [
                'attr' => [
                    'class' => 'form-select form-select-lg',
                ],
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $r)
                {
                    return $r->createQueryBuilder('i')
                            ->where('i.user = :user')
                            ->orderBy('i.name', 'ASC')
                            ->setParameter('user', $this->token->getToken()->getUser());
                },
                'label' => 'Les ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer une recette'
            ]);
    }


    /**
     * This method is used to configure the options
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
