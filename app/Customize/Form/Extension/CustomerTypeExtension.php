<?php

// app/Customize/Form/Extension/CustomerTypeExtension.php
namespace Customize\Form\Extension;

use Eccube\Form\Type\Admin\CustomerType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class CustomerTypeExtension extends AbstractTypeExtension
{
    private $conn;
    public function __construct(Connection $con)
    {
        $this->conn = $con;
    }

    /*
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    */


    public static function getExtendedTypes(): iterable
    {
        // 明示的に fully qualified name を返す
        return [\Eccube\Form\Type\Admin\CustomerType::class];
    }

    // EC-CUBEの古い互換層が必要な場合がある
    public function getExtendedType()
    {
        return \Eccube\Form\Type\Admin\CustomerType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ranks = $this->conn->fetchAllAssociative('SELECT id, name FROM mtb_customer_rank ORDER BY sort_no ASC');
        
        /*
        $ranks = $this->em->getConnection()->fetchAllAssociative(
            'SELECT id, name FROM mtb_customer_rank ORDER BY sort_no ASC'
        );
        */

        $choices = [];
        foreach ($ranks as $r) {
            $choices[$r['name']] = $r['id'];
        }

        $builder->add('rank_id', ChoiceType::class, [
            'label' => '会員ランク',
            'choices' => $choices,
            'required' => true,
        ]);
    }
}
