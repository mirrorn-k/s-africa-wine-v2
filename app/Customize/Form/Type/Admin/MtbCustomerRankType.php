<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\MtbCustomerRank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MtbCustomerRankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'コード',
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => '名称',
            ])
            ->add('discount_rate', NumberType::class, [
                'label' => '割引率(%)',
                'scale' => 2,
                'required' => false,
            ])
            ->add('sort_no', IntegerType::class, [
                'label' => '表示順',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MtbCustomerRank::class,
        ]);
    }
}
