<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class MergeCsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                "label" => "Fusion type",
                'choices'  => [
                    'Sequential' => "Sequential",
                    'Interlaced' => "Interlaced"
                ]
            ])
            ->add('csv1', FileType::class, [
                "label" => "CSV 1",
                "constraints" => [
                    new NotBlank(),
                    new File([
                        //"mimeTypes" => ["text/plain", "text/csv"],
                        "mimeTypesMessage" => "Please attach a CSV file !"
                    ])
                ]
            ])
            ->add('csv2', FileType::class, [
                "label" => "CSV 2",
                "constraints" => [
                    new NotBlank(),
                    new File([
                        //"mimeTypes" => ["text/plain", "text/csv"],
                        "mimeTypesMessage" => "Please attach a CSV file !"
                    ])
                ]
            ])
            ->add('mergeCsvName', TextType::class, [
                "label" => "Merge csv name",
                "constraints" => [
                    new NotBlank()
                ]
            ])
            ->setMethod("POST")
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}
