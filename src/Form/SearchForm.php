<?php


namespace App\Form;


use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('departement', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Exemple : 31'
                ]
            ])
            ->add('produitVise', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'choices' => [
                    'Eau chaude sanitaire' => 'Eau chaude sanitaire',
                    'Eau chaude sanitaire et chauffage' => 'Eau chaude sanitaire et chauffage',
                    'Eau chaude sanitaire et électricité' => 'Eau chaude sanitaire et électricité'
                ],
                //quelle est la classe à afficher ici ?
                //quelle propriété utiliser pour les <option> dans la liste déroulante ?
                'placeholder' => ' Choisir le produit Visé '
            ])
            /*->add('eauChaudeSanitaire', CheckboxType::class, [
                'label' => 'Eau chaude sanitaire',
                'required' => false,
            ])
            ->add('eauChaudeSanitaireChauffage', CheckboxType::class, [
                'label' => 'Eau chaude sanitaire et chauffage',
                'required' => false,
            ])
            ->add('eauChaudeSanitaireElectricite', CheckboxType::class, [
                'label' => 'Eau chaude sanitaire et électricité',
                'required' => false,
            ])*/
            ->add('energie', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'choices' => [
                    'Fioul' => 'Fioul',
                    'Gaz' => 'Gaz',
                    'Electricité' => 'Electricité',
                    'Bois / granule' => 'Bois / granule',
                ],
                //quelle est la classe à afficher ici ?
                //quelle propriété utiliser pour les <option> dans la liste déroulante ?
                'placeholder' => ' Choisir l\'energie '
            ]);

    }



}