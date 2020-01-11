<?php

namespace App\Form;

use App\Entity\Commande;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fournisseur', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=>'App\Entity\Fournisseur',
                'query_builder'=>function(EntityRepository $repository){ return $repository->liste();},
                'choice_label'=>'nom'
            ])
            ->add('date', DateType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"La date de commande", 'autocomplete'=>"off"], 'widget'=>'single_text', 'html5'=>true, 'required'=>true])
            ->add('prescription', TextType::class,['attr'=>['class'=>'form-control', 'placholder'=>'Les prescriptions de la commande', 'autocomplete'=>"off"]])
            ->add('montant', IntegerType::class,['attr'=>['class'=>'form-control', 'placeholder'=>'le montant de la commande', 'autocomplte'=>"off"]])
            ->add('livraison', CheckboxType::class,['attr'=>['class'=>'form-check-input'],'required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
