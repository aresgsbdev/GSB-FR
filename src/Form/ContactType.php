<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('villr')
            ->add('codePostal')
            ->add('dateEmbauche', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'dd-MM-yyyy',
                 // 2. Disable HTML5 option
                'html5' => false,
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('matricule')
            ->add('telephone')
            ->add('password',
                RepeatedType::class,
                array(
                    'type'              => PasswordType::class,
                    'mapped'            => false,
                    'first_options'     => array('label' => 'New password'),
                    'second_options'    => array('label' => 'Confirm new password'),
                    'invalid_message' => 'The password fields must match.',
                )
            )
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
