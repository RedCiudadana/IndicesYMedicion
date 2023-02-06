<?php

declare(strict_types=1);

namespace App\Admin\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SurveySubmitFilterExtension extends AbstractAdminExtension {
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

    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query): void
    {
        /**
         * @var SonataUserUser
         */
        $user = $this->token->getToken()->getUser();

        // $rootAlias = current($query->getRootAliases());

        // $query->andWhere(
        //     $query->expr()->eq($rootAlias . '.submittedBy', ':user')
        // );
        // $query->setParameter('user', $this->getSecurityHandler()->isGranted('ROLE_ADMIN'));
    }
}
