<?php

namespace App\Form;

use App\Entity\Announcement;
use App\Enum\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnnouncementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class ,[
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Un titre est obligatoire !'
                        ]
                    )
                ]
            ])
            ->add('description', TextareaType::class ,[
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Un titre est obligatoire !'
                        ]
                    )
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'allow_delete' => false,  
            ])
            ->add('category', EnumType::class, [
                'class' => Category::class , 
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Une catégorie est obligatoire !'
                        ]
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announcement::class,
        ]);
    }
}
