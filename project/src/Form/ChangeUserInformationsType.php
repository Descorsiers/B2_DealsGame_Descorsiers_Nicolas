<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeUserInformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Un email est obligatoire !'
                        ]
                    )
                ]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Un prénom est obligatoire !'
                        ]
                    )
                ]
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Un nom est obligatoire !'
                        ]
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
