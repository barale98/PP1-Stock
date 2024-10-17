<?php

namespace App\Form;

use App\Entity\Maquinaria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaquinariaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre de la maquinaria',
            ])
            ->add('marca', TextType::class, [
                'label' => 'Marca de la maquinaria',
            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción',
            ])
            ->add('cantidad', IntegerType::class, [
                'label' => 'Cantidad disponible',
            ])
            ->add('aniosUso', IntegerType::class, [
                'label' => 'Años de uso',
            ])
            ->add('ultimoService', null, [
                'widget' => 'single_text',
                'label' => 'Último service (opcional)',
                'required' => false,
            ])
            ->add('imagen', FileType::class, [
                'label' => 'Imagen de la maquinaria (opcional)',
                'required' => false,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maquinaria::class,
        ]);
    }
}
