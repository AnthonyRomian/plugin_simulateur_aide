<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom : '
                ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom : '
            ])
            ->add('code_postal', TextType::class, [
                    'label' => 'Code postal : '
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville : '
            ])
            ->add('tel', TextType::class, [
                'label' => 'Tel : '
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : '
            ])
            ->add('proprietaire', ChoiceType::class,[
                'label' => 'Etes-vous propriétaire ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'multiple' => false,
                'expanded' => true

            ])
            ->add('type_bien', ChoiceType::class,[
                'label' => 'Votre logement est une : ',
                'choices' => [
                    'Maison' => 'Maison',
                    'Appartement' => 'Appartement'
                ],
                'multiple' => false,
                'expanded' => true

            ])
            ->add('ancienneteEligible', ChoiceType::class,[
                'label' => 'Votre logement a t-il plus de 2 ans ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'multiple' => false,
                'expanded' => true

            ])
            ->add('produit_vise', ChoiceType::class,[
                'label' => 'Produit désiré : ',
                'choices' => [
                    'Eau chaude sanitaire' => 'Eau chaude sanitaire',
                    'Eau chaude sanitaire et chauffage' => 'Eau chaude sanitaire et chauffage'
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('nbre_salle_bain', IntegerType::class, [
                'label' => 'Nombre de salle de bain :'
            ])
            ->add('energie', ChoiceType::class,[
                'choices' => [
                    'Fioul' => 'Fioul',
                    'Gaz' => 'Gaz',
                    'Electricité' => 'Electricité',
                    'Bois_granule' => 'Bois / granule',
                ],
                'multiple' => false
            ])
            ->add('chauffage', ChoiceType::class,[
                'choices' => [
                    'radiateur à eau' => 'radiateur à eau',
                    'radiateur electrique' => 'radiateur electrique',
                    'plancher chauffant' => 'plancher chauffant',
                    'Autres' => 'Autres',
                ],
                'multiple' => false
            ])
            ->add('nbre_pers_foyer', IntegerType::class, [
                'label' => 'Nombre de personne au foyer :'
            ])
            ->add('revenu_fiscal', IntegerType::class, [
                'label' => 'Votre revenu fiscal de référence :'
            ])
        ;
        ;
        //->add('date_simulation')
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
