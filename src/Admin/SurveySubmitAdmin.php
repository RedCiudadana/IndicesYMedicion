<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\SonataUserUser;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\Form\Type\EqualType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class SurveySubmitAdmin extends AbstractAdmin
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

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            // ->add('submittedData')
            ->add('submittedBy')
            ->add('measurementIndex')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            // ->add('submittedData')
            ->add('submittedBy')
            ->add('measurementIndex')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'submit' => [
                        'template' => 'survey_submit/list__action_submit.html.twig'
                    ]
                ],
            ])
            ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('id')
            // ->add('submittedData')
            ->add('submittedBy', null, [
                'disabled' => true
            ])
            ->add('measurementIndex', null, [
                'disabled' => true
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            // ->add('submittedData')
            ->add('submittedBy')
            ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('submitSurvey', '{id}/updateSubmit');
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        /**
         * @var Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery
         */
        $query = parent::configureQuery($query);

        $queryBuilder = $query->getQueryBuilder();

        /**
         * @var SonataUserUser
         */
        $user = $this->token->getToken()->getUser();

        if (!$this->authorizationChecker->isGranted('ROLE_RESEARCH_LEADER')) {
            $rootAlias = current($queryBuilder->getRootAliases());

            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq($rootAlias . '.submittedBy', ':user')
            );

            $queryBuilder->setParameter('user', $user->getId());
        }

        return $query;
    }

    // protected function configureDefaultFilterValues(array &$filterValues): void
    // {
    //     /**
    //      * @var SonataUserUser
    //      */
    //     $user = $this->token->getToken()->getUser();

    //     dump($user);
    //     dump($this->authorizationChecker->isGranted('ROLE_ADMIN'));

    //     // Assuming security context injected
    //     if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
    //         $filterValues['submittedBy'] = [
    //             'type'  => EqualType::TYPE_IS_EQUAL, // 1
    //             'value' => $user->getId()
    //         ];
    //     }
    // }
}
