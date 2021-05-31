<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Messagerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet', TextType::class, [
                'attr'=> [
                    'class'=>'text-primary',
                ],
                'label'=>'Objet'
                
            ])

            
            
            ->add('message')
            
            // ->add('utilisateurMessagerie',EntityType::class,[
            //     "class" => User::class,
            //     "choice_label" => "nom"
            // ])

            ->add('utilisateurDestinataireMessagerie',EntityType::class,[
                "class" => User::class,
                "choice_label" => "nom"

            ])
            // ->add('utilisateurExpediteurMessagerie',EntityType::class,[
            //     "class" => User::class,
            //     "choice_label" => "nom"
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Messagerie::class,
        ]);
    }
}