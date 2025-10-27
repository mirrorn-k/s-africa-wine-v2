<?php
namespace Customize\Form\Extension;

use Eccube\Form\Type\Admin\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('origin', TextType::class, [
                'label' => '原産国',
                'required' => false,
            ])
            ->add('alcohol', TextType::class, [
                'label' => 'アルコール度数',
                'required' => false,
            ])
            ->add('capacity', TextType::class, [
                'label' => '容量',
                'required' => false,
            ])
            ->add('vintage', TextType::class, [
                'label' => 'ヴィンテージ',
                'required' => false,
            ])
            ->add('award', TextareaType::class, [
                'label' => '受賞／評価',
                'required' => false,
            ]);
    }
}
