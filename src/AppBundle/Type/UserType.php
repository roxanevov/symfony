<?php

namespace AppBundle\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
            ->add('roles',TextType::class ,['label'=>'Roles (separated by commas (,))'])
            ->add('save', SubmitType::class)
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray){
                    if(!empty($rolesAsArray)){
                        //Form Model to view | array to string
                        return implode(',',$rolesAsArray);
                    }

                },
                function ($rolesAsString){
                    //Form view to model | string to array
                    return explode(',', $rolesAsString);
                }
            ))
        ;
    }
}