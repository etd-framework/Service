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
use Joomla\Registry\Registry;

/**
 * Fournisseur du service Configuration
 */
class ConfigurationProvider implements ServiceProviderInterface {

    /**
     * Instance de la configuration
     *
     * @var Registry
     */
    private $config;

    /**
     * Constructeur.
     *
     * @throws \RuntimeException
     */

    public function __construct() {

        // On définit le chemin vers le fichier de configuration de l'application.
        $file = JPATH_ROOT . '/etc/config.json';

        // On vérifit que le fichier existe et qu'il est lisible.
        if (!is_readable($file)) {
            throw new \RuntimeException(sprintf('Le fichier de configuration %s n\'existe pas ou est illisible.', $file));
        }

        // On charge le fichier dans un objet.
        $configObject = json_decode(file_get_contents($file));
        if ($configObject === null) {
            throw new \RuntimeException(sprintf('Impossible d\'analyser le fichier de configuration %s.', $file));
        }

        $config = (new Registry)->loadObject($configObject);
        $this->config = $config;

    }

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $container->set('config', function () {
            return $this->config;
        }, true, true);

    }
}