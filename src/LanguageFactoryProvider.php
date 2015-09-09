<?php
/**
 * Part of the ETD Framework Service Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Service;

use EtdSolutions\Language\LanguageFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Fournisseur du service pour l'objet LanguageFactory
 */
class LanguageFactoryProvider implements ServiceProviderInterface {

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $container->share(
            'Joomla\\Language\\LanguageFactory',
            function () use ($container) {

                $factory = new LanguageFactory;

                /** @var \Joomla\Registry\Registry $config */
                $config = $container->get('config');

                $baseLangDir = $config->get('language.basedir');
                $defaultLang = $config->get('language.default', 'fr-FR');

                if ($baseLangDir) {
                    $factory->setLanguageDirectory($baseLangDir);
                }

                $factory->setDefaultLanguage($defaultLang);

                return $factory;
            }, true
        );

        $container->alias('language', 'Joomla\\Language\\LanguageFactory');
    }
}
