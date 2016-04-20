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
use Joomla\Input\Input;
use Joomla\Session\Handler\DatabaseHandler;
use Joomla\Session\Session;
use Joomla\Session\Storage\NativeStorage;

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

        $config = $container->get('config');

        $store = new NativeStorage(new DatabaseHandler($container->get('db')), [
            'name'            => md5($config->get('sitename')),
            'cookie_domain'   => $config->get('cookie.domain'),
            'cookie_path'     => $config->get('cookie.path'),
            'cookie_httponly' => $config->get('cookie.httponly'),
            'cookie_lifetime' => $config->get('cookie.lifetime'),
            'cookie_secure'   => $config->get('cookie.secure')
        ]);

        $container->set('Joomla\\Session\\StorageInterface', $store);
        $container->alias('storage', 'Joomla\\Session\\StorageInterface');

        $container->set('Joomla\\Session\\SessionInterface', function () use ($store, $config) {

            $input   = new Input();
            $session = new Session($input, $store, null, [
                'name'   => md5($config->get('sitename')),
                'expire' => $config->get('session_expire')
            ]);

            return $session;

        }, true, true);

        // On crée un alias pour la session.
        $container->alias('session', 'Joomla\\Session\\SessionInterface');
    }
}