<?php

namespace App\Form;

use App\DTO\AuthorSearchCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom auteur : ',
                'required' => false,
                'empty_data' => "",
            ])
            ->add('limit', IntegerType::class, [
                'label' => 'Nombre de résultats par page:',
                'required' => false,
                'empty_data' => "15",
            ])
            ->add('page', IntegerType::class, [
                'label' => 'Page :',
                'required' => false,
                'empty_data' => "1",
            ])
            ->add('orderBy', ChoiceType::class, [
                'label' => 'Trier par :',
                'choices' => [
                    'identifiant' => 'id',
                    'nom' => 'name',
                    'date de création' => 'createdAt',
                    'date de mise à jour' => 'updatedAt',
                ],
                'required' => false,
                'empty_data' => "name",
            ] )
            ->add('direction', ChoiceType::class, [
                'label' => 'Direction :',
                'choices' => [
                    'Croissant' => 'ASC',
                    'Décroissant' => 'DESC',
                ],
                'required' => false,
                'empty_data' => "ASC",
            ])
            ->add('updatedAtStart', DateTimeType::class, [
                'label' => 'Date de mise à jour début',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('updatedAtStop', DateTimeType::class, [
                'label' => 'Date de mise à jour fin',
                'required' => false,
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AuthorSearchCriteria::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
    /**
     * !! ON DESACTIVE LE PREFIX
     * Désactive le prefix du formulaire, permettant d'avoir
     * de belle urls.
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
