<?php

namespace App\Form;

use App\Entity\Oeuvre;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'œuvre'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('date_creation', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo (JPEG ou PNG)',

                // Non obligatoire
                'required' => false,

                // Validation des fichiers (extension/mime type)
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG ou PNG)',
                    ])
                ],
                'data_class' => null,
            ])
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'nom', // Affiche le champ `nom` comme choix dans le formulaire
                'placeholder' => 'Choisissez un artiste', // Facultatif : ajoute un placeholder
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}

