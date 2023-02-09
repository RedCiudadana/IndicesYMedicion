<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

class ChoiceValue {
    private $key;
    private $value;

    /**
     * Get the value of key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the value of key
     *
     * @return  self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the value of value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}

final class SurveyQuestionAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('formType')
            ->add('formOptions')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('formType')
            ->add('formOptions')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
            ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('description')
            ->add('formType', ChoiceFieldMaskType::class, [
                'choices' => [
                    'numero' => 'number',
                    'opciones' => 'choice',
                ],
                'map' => [
                    'number' => ['min', 'max'],
                    'choice' => ['choices'],
                ],
                'placeholder' => 'Choose an option',
                'required' => false
            ])
            ->add('min')
            ->add('max')
            ->add('choices', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                // 'prototype' => true,
                // 'prototype_data' => new ChoiceValue()
            ])
            ->add('formOptions', null, [
                'disabled' => false
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('formType')
            ->add('formOptions')
            ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        if ($this->isChild()) {
            return;
        }

        // This is the route configuration as a parent
        $collection->clear();
    }
}
