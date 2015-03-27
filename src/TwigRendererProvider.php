<?php
/**
 * Part of the ETD Framework Service Package
 *
 * @copyright   Copyright (C) 2015 ETD Solutions, SARL Etudoo. Tous droits réservés.
 * @license     Apache License 2.0; see LICENSE
 * @author      ETD Solutions http://etd-solutions.com
 */

namespace EtdSolutions\Service;

use EtdSolutions\EtdInterfaces\Renderer\TwigExtension;
use Joomla\Application\AbstractApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Renderer\TwigRenderer;

/**
 * Fournisseur du service TwigRenderer
 */
class TwigRendererProvider implements ServiceProviderInterface {

    /**
     * L'objet Application
     *
     * @var AbstractApplication
     */
    private $app;

    /**
     * Constructeur.
     *
     * @param AbstractApplication $app L'objet Application
     */
    public function __construct(AbstractApplication $app) {
        $this->app = $app;
    }

    /**
     * Enregistre le fournisseur de service auprès du container DI.
     *
     * @param Container $container Le container DI.
     *
     * @return Container Retourne l'instance pour le chainage.
     */
    public function register(Container $container) {

        $container->set('Joomla\\Renderer\\RendererInterface', function (Container $container) {

                /* @type \Joomla\Registry\Registry $config */
                $config = $container->get('config');

                // On instancie l'objet renderer.
                $renderer = new TwigRenderer($config->get('template'));

                // On ajoute nos extensions Twig.
                $renderer->getRenderer()->addExtension(new TwigExtension($this->app));

                // On définit l'objet Lexer.
                $renderer->getRenderer()->setLexer(
                    new \Twig_Lexer($renderer->getRenderer(), ['delimiters' => [
                        'tag_comment' => ['{#', '#}'],
                        'tag_block' => ['{%', '%}'],
                        'tag_variable' => ['{{', '}}']
                    ]])
                );

                return $renderer;

        }, true, true);

        // On crée un alias du renderer.
        $container->alias('renderer', 'Joomla\\Renderer\\RendererInterface');
    }
}