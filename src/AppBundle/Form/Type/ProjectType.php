<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 08/04/2017
 * Time: 18:23
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('repository')
            ->add('link')
            ->add('tags', TagsType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
        ));
    }
}