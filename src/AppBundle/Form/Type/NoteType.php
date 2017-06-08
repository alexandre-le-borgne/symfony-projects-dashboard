<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 10/04/2017
 * Time: 17:11
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('tags', TagsType::class)
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'simplemde',
                ],
                'required'    => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Note::class,
        ));
    }
}