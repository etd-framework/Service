<?php
/**
 * Part of the ETD Framework Service Package
 *
 * @copyright   Copyright (C) 2016 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Fournisseur du service Logger
 */
class LoggerProvider implements ServiceProviderInterface {

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $config = $container->get('config');

        // On instancie le logger si besoin.
        if ($config->get('logger.enable', false)) {

            $container->set('Psr\\Log\\LoggerInterface', function () use ($config) {

                $logger = new Logger($config->get('logger.name', 'default'));

                if (is_dir(JPATH_LOGS)) {
                    $logger->pushHandler(new StreamHandler(JPATH_LOGS . "/" . sprintf($config->get('logger.file'), date('d-m-Y')), ($config->get('debug') ? Logger::DEBUG : Logger::WARNING)));
                } else { // If the log path is not set, just use a null logger.
                    $logger->pushHandler(new NullHandler, ($config->get('debug') ? Logger::DEBUG : Logger::WARNING));
                }

                return $logger;

            }, true, true);

            // On crée un alias pour le logger.
            $container->alias('logger', 'Psr\\Log\\LoggerInterface');

        }
    }
}
