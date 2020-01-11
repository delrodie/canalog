<?php

namespace App\Form;

use App\Entity\Operation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->type = $options['type'];
        $type = $this->type;

        $builder
            ->add('date', DateType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"La date de l'opération", 'autocomplete'=>"off"], 'widget'=>'single_text', 'html5'=>true, 'required'=>true])
            ->add('montant', IntegerType::class,['attr'=>['class'=>"form-control", 'placeholder'=>"le montant de l'opération", 'autocomplete'=>"off"]])
            ->add('type', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=>'App\Entity\Type',
                'query_builder'=>function(EntityRepository $repository) use($type){ return $repository->findByLibelle($type);},
                'choice_label'=>'libelle'
            ])
            ->add('mode', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=>'App\Entity\Mode',
                'query_builder'=>function(EntityRepository $repository){ return $repository->liste();},
                'choice_label'=>'libelle'
            ])
            ->add('officine', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=>'App\Entity\Officine',
                'query_builder'=>function(EntityRepository $repository){ return $repository->liste();},
                'choice_label'=>'nom'
            ])
            ->add('assurance',TextType::class,['attr'=>['class'=>'form-control','placeholder'=>"L'assurance", 'autocomplete'=>'off'],'required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
            'type'  => null,
        ]);
    }
}
