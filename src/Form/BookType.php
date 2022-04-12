<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre:  '
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :  '
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Créé le :  '
            ])
            ->add('updatedAt', DateTimeType::class, [
                'label' => 'Mis à jour le :  '
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix :  '
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'label' => 'Auteur :  ',
                'choice_label' => 'name'
            ])
            ->add('categories', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
