<?php

/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 01/08/16
 * Time: 12:23 م
 */
namespace Test\FrontBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //pour déplacer la logique
        $builder
            ->add('name',null,array('label'=>'Nom de l\'album '))
            ->add('type',null,array('label'=>'Type de l\'album'))
            ->add('artist',null,array('label'=>'Artist'))
            ->add('duration',null,array('label'=>'Duration de l\'album'))
            ->add('released','date',array('label'=>'Date de l\'album'))
            ->add('submit','submit')// les add pour la personalisation
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        //si on ajoute des paramétre il ne sera pas un problème 
        //ajouter l'option sur le resolver pour le dire que le data class c'est le base vers le name space vers l'entity
        $resolver->setDefaults(array(
            'data_class'=>'Test\FrontBundle\Entity\Sheet'
        ));
    }

    public function getName()
    {
        //renommer le formulaire
        return 'sheet_form';
    }

}