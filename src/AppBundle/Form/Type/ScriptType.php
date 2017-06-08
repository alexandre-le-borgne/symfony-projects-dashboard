<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 15/04/2017
 * Time: 19:11
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Script;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScriptType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('tags', TagsType::class)
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'aceeditor',
                ],
                'required'    => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Script::class,
        ));
    }
}