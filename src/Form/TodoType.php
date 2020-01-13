<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Title',
                // 'invalid_message' => 'Title has to be string'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'required' => false
            ])
            // ->add('createdAt', DateTimeType::class)
            ->add('inStatus', ChoiceType::class, [
                'label' => 'Completed',
                'choices' => [
                    'Yes' => 1,
                    'No' => 0
                ]
                /*'false_values' => [0],
                'value' => 0,
                'data' => 0*/
            ])
            /*->add('save', SubmitType::class, [
                'label' => 'Add item'
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
            'allow_extra_fields' => true
        ]);
    }
}
