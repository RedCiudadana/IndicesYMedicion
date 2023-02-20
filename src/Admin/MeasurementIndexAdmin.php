<?php

declare(strict_types=1);

namespace App\Admin;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Extension\ChoiceTypeExtension;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class MeasurementIndexAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    private $token;

    /**
     * {@inheritdoc}
     */
    private $authorizationChecker;

    public function __construct(TokenStorageInterface $token, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->token = $token;
        $this->authorizationChecker = $authorizationChecker;
    }

    protected function configureTabMenu(MenuItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        // $menu->addChild('View MeasurementIndex', $admin->generateMenuUrl('show', ['id' => $id]));

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Editar indice', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Administrar preguntas', $admin->generateMenuUrl('admin.survey_question.list', ['id' => $id]));
        }

        if ($this->authorizationChecker->isGranted('ROLE_RESEARCH')) {
            $menu->addChild('Llenar encuesta', $admin->generateMenuUrl('admin.measurement_index.submitSurvey', ['id' => $id]));
        }
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title')
            ->add('description')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        if (!$this->getListMode()) {
            $this->setListMode('mosaic');
        }
        // $this->getRequest()->getSession()->set(sprintf('%s.list_mode', $this->getCode()), 'mosaic');

        $list
            ->add('id')
            ->add('title')
            ->add('description')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    // 'delete' => [],
                    'submit' => [
                        'template' => 'measurement_index/list__action_submit.html.twig'
                    ],
                    'data' => [
                        'template' => 'measurement_index/list__action_submit_data.html.twig'
                    ]
                ],
            ])
            ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            // ->add('id')
            ->add('title')
            ->add('description')
            ->add('whatWeMeasure')
            ->add('howWeMeasure')
            ->add('researchers')
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('whatWeMeasure')
            ->add('howWeMeasure')
            ->add('researchers')
            ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('submitSurvey', '{id}/submit_survey')
            ->add('submitSurveyData', '{id}/submit_survey_data')
        ;

        $collection
            // ->remove('show')
        ;
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        /**
         * @var Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery|
         */
        $query = parent::configureQuery($query);

        /**
         * @var QueryBuilder
         */
        $queryBuilder = $query->getQueryBuilder();

        /**
         * @var SonataUserUser
         */
        $user = $this->token->getToken()->getUser();

        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $rootAlias = current($queryBuilder->getRootAliases());

            $queryBuilder->innerJoin($rootAlias . '.researchers', 'r');

            $queryBuilder->andWhere(
                $queryBuilder->expr()->in('r.id', ':user')
            );

            $queryBuilder->setParameter('user', $user->getId());
        }

        return $query;
    }
}
