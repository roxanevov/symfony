<?php

namespace AppBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname')
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'first_options'=> ['label'=>'Password'],
                'second_options'=>['label'=>'Repeat Password'],
                'invalid_message'=>'The password fields must match.'

            ])
            ->add('username',EmailType::class,['label'=>'email'])
            ->add('save', SubmitType::class);
    }
}