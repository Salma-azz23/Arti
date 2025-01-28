<?php

namespace App\Form;

use App\Entity\Visiteur;
use App\Entity\Artiste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class VisiteurType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $artistes = $this->entityManager->getRepository(Artiste::class)->findAll();
        $choices = [];
        foreach ($artistes as $artiste) {
            $choices[$artiste->getNom()] = $artiste->getId();
        }

        $builder
            ->add('nom')
            ->add('email', null, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'email ne peut pas être vide']),
                    new Email(['message' => 'L\'email doit être valide']),
                ],
            ])
            // Ajoutez la vérification d'unicité ici
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $visiteur = $form->getData();

                    $existingVisiteur = $this->entityManager->getRepository(Visiteur::class)->findOneBy(['email' => $visiteur->getEmail()]);
                    if ($existingVisiteur) {
                        $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
                    }
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visiteur::class,
        ]);
    }
}

