<?php

namespace App\Form;

use App\Entity\PostComponent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class PostDiscriminatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('discriminatorType', ChoiceType::class, [
                'required' => true,
                "data" => "default",
                'choices' => [
                    'default' => 'default',
                    'text' => 'text',
                    'image' => 'image',
                ]
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($builder) {
            $type = strlen($event->getData()['discriminatorType'] ?? "text") > 0 ? $event->getData()['discriminatorType'] ?? "text" : "text";

            if (in_array($type, ["text", "default"])) {
                $event->getForm()
                    ->add("title", TextType::class)
                    ->add("content", TextareaType::class)
                ;
            }

            if ($type == "image") {
                $event->getForm()
                    ->add("title", TextType::class)
                    ->add("content", TextareaType::class)
                    ->add('photo', DropzoneType::class, [
                        'mapped' => false
                    ])
                ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => PostComponent::class]);
    }
}
