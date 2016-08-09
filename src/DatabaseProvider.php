<?php
/**
 * Part of the ETD Framework Service Package
 *
 * @copyright   Copyright (C) 2016 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Service;

use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * Fournisseur du service Database
 */
class DatabaseProvider implements ServiceProviderInterface {

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $container->set('Joomla\\Database\\DatabaseDriver', function () use ($container) {

            $config = $container->get('config');

            $db = DatabaseDriver::getInstance(array(
                'driver'   => $config->get('database.driver'),
                'host'     => $config->get('database.host'),
                'user'     => $config->get('database.user'),
                'password' => $config->get('database.password'),
                'database' => $config->get('database.name'),
                'prefix'   => $config->get('database.prefix', '')
            ));

            // Logger
            if ($container->has('logger')) {
                $db->setLogger($container->get('logger'));
            }

            // Profiler
            if ($container->has('profiler')) {
                $db->setQuery("SET GLOBAL profiling = 1")
                   ->execute();
            }

            // Debug
            $db->setDebug($config->get('database.debug', false));

            return $db;

        }, true, true);

        // On crée un alias pour la base de données.
        $container->alias('db', 'Joomla\\Database\\DatabaseDriver');
    }
}
