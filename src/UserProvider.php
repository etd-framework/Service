<?php
/**
 * Part of the ETD Framework Service Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Service;

use EtdSolutions\User\User;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Fournisseur du service User
 */
class UserProvider implements ServiceProviderInterface {

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $container->set('EtdSolutions\\User\\User', function () use ($container) {

            $session = $container->get('session');
            $db      = $container->get('db');

            $user = new User($session, $db);

            return $user;

        }, true, true);

        // On crée un alias pour la session.
        $container->alias('user', 'EtdSolutions\\User\\User');
    }
}