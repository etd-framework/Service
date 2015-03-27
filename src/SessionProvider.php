<?php
/**
 * Part of the ETD Framework Service Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Session\Session;

/**
 * Fournisseur du service Session
 */
class SessionProvider implements ServiceProviderInterface {

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $container->set('Joomla\\Session\\Session', function () use ($container) {

            $config = $container->get('config');

            $session = Session::getInstance('Database', [
                'name'          => $config->get('sitename'),
                'expire'        => $config->get('session_expire'),
                'force_ssl'     => $config->get('force_ssl'),
                'cookie_domain' => $config->get('cookie.domain'),
                'cookie_path'   => $config->get('cookie.path'),
                'db'            => $container->get('db')
            ]);

            return $session;

        }, true, true);

        // On crée un alias pour la session.
        $container->alias('session', 'Joomla\\Session\\Session');
    }
}