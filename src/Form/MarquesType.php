<?php

namespace App\Form;

use App\Entity\Marques;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MarquesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
            ->add('lastname',TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
            ->add('company_name',TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
            ->add('site_web',TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
            ->add('telephone',TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
            ->add('email',TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
            ->add('password',TextType::class, [
                'attr' => [
                    'class' => 'form_style'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marques::class,
        ]);
    }
}
