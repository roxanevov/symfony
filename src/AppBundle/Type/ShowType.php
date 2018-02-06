<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 06/02/2018
 * Time: 08:55
 */

namespace AppBundle\Type;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;


class ShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'name',
            ))
            ->add('abstract')
            ->add('country', CountryType::class)
            ->add('author')
            ->add('releasedDate', DateType::class)
            ->add('tmpPicture', FileType::class,['label'=>'Main Picture']);
    }

}

