<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Ressources;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourcePostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Titre',
                    'class' => 'form-control'
                )
           ))
            ->add('contenu', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Contenu',
                    'class' => 'form-control'
                )
           ))
            // ->add('dateCreation', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('estPubliee')
            // ->add('estValidee')
            // ->add('estRestreinte')
            // ->add('estExploitee')
            // ->add('estArchivee')
            // ->add('estDesactivee')
            ->add('multimedia', TextType::class, array(
                'label' => false,
                'required' => false,
                'empty_data'  => '',
                'attr' => array(
                    'placeholder' => 'Lien de votre image (.png)',
                    'class' => 'form-control'
                )
           ))
            // ->add('idUtilisateur', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('idCategorie', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Categories::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'attr' => array(
                    'class' => 'form-control custom-dropdown'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressources::class,
        ]);
    }
}
