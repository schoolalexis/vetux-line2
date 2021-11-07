<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvalidCsvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('invalid', ChoiceType::class, [
                "label" => "Get",
                'choices'  => [
                    'Not major customer(s)' => "notMajor",
                    'Customer(s) with invalid size' => "invalidSize",
                    'Customer(s) with invalid cc number' => "invalidCcNumber",
                ]
            ])
            ->add('csv', FileType::class, [
                 "label" => "CSV",
                 "constraints" => [
                     new NotBlank(),
                     new File([
                         //"mimeTypes" => ["text/plain", "text/csv"],
                         "mimeTypesMessage" => "Please attach a CSV file !"
                     ])
                 ]
            ])
            ->setMethod("POST");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
