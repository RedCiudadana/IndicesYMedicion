<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\Extension\ChoiceTypeExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class MeasurementIndexAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('level')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('level')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            // ->add('id')
            ->add('title')
            ->add('description')
            ->add('level', ChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'style' => 'display: flex; justify-content: center'
                ],
                'choices' => array_combine([
                    'a+1 Demuestra capcidad de tecnologia, tiene mas de 2 computadoras, conexion a internet y servicio de IT+',
                    'a+2 Demuestra capcidad de tecnologia, tiene mas de 2 computadoras, conexion a internet y servicio de IT+',
                    'a+3 Demuestra capcidad de tecnologia, tiene mas de 2 computadoras, conexion a internet y servicio de IT+',
                ], range(0, 2))
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('level')
            ;
    }
}
