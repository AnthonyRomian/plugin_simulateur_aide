<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Nom : ',
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Prénom : ',
            ])
            ->add('code_postal', TextType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Code postal : ',
            ])
            ->add('ville', TextType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Ville : ',
            ])
            ->add('tel', TextType::class, [
                'required' => false,
                    'label_attr' => [
                        'class' => 'label'
                    ],
                'label' => 'Tel : ',
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Email : ',
            ])
            ->add('proprietaire', ChoiceType::class,[
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Etes-vous propriétaire ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => true

            ])
            ->add('type_bien', ChoiceType::class,[
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Votre logement est une : ',
                'choices' => [
                    'Maison' => 'Maison',
                    'Appartement' => 'Appartement'
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'Maison'

            ])
            ->add('ancienneteEligible', ChoiceType::class,[
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Votre bien a t-il plus de 2 ans ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => true

            ])
            ->add('produit_vise', ChoiceType::class,[
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Produit désiré : ',
                'choices' => [
                    'Eau chaude sanitaire' => 'Eau chaude sanitaire',
                    'Eau chaude sanitaire et chauffage' => 'Eau chaude sanitaire et chauffage'
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'Eau chaude sanitaire'
            ])
            ->add('nbre_salle_bain', IntegerType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Nombre de salle de bain :'
            ])
            ->add('energie', ChoiceType::class,[
                'label' => 'Energie actuelle',
                'label_attr' => [
                    'class' => 'label'
                ],
                'choices' => [
                    'Selectionnez votre energie' => 'Selectionnez votre energie',
                    'Fioul' => 'Fioul',
                    'Gaz' => 'Gaz',
                    'Electricité' => 'Electricité',
                    'Bois / granule' => 'Bois / granule',
                ],
                'multiple' => false,
                'row_attr' => [
                    'id' => 'ElemChauffage1',
                    'style' => 'display: none'
                ],
            ])
            ->add('chauffage', ChoiceType::class,[
                'label' => 'Type de chauffage actuel',
                'label_attr' => [
                    'class' => 'label'
                ],
                'choices' => [
                    'Radiateur à eau' => 'Radiateur à eau',
                    'Radiateur electrique' => 'Radiateur electrique',
                    'Plancher chauffant' => 'Plancher chauffant',
                    'Autres' => 'Autres',
                ],
                'multiple' => true,
                'expanded' => true,
                'row_attr' => [
                    'id' => 'ElemChauffage2',
                    'style' => 'display: none'
                ],
            ])
            ->add('nbre_pers_foyer', IntegerType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Nombre de personne au foyer :'
            ])
            ->add('revenu_fiscal', IntegerType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'label'
                ],
                'label' => 'Revenu fiscal de référence :'
            ])
            ->add('agreeTerms', ChoiceType::class,[
                'label' => ' ',
                'choices' => [
                    'J\'autorise l\'utilisation de mes informations pour le calcul de mes aides' => true,
                ],
                'multiple' => true,
                'expanded' => true,
                'data' => [true],
            ])
            ->add('agreeEmail', ChoiceType::class,[
                'label' => ' ',
                'choices' => [
                    'Je veux recevoir le résultat de ma simulation par mail' => true,
                ],
                'multiple' => true,
                'expanded' => true,
                'data' => [true],
            ]);
            $builder->get('agreeEmail')
            ->addModelTransformer(new CallbackTransformer(
                function ($agreeEmail) {
                    return (array) $agreeEmail;
                },
                function ($agreeEmail) {
                    return (bool) $agreeEmail;
                }
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
