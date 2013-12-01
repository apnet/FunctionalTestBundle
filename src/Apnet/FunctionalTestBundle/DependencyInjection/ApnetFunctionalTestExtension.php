<?php

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ApnetFunctionalTestExtension extends Extension
{

  /**
   * Loads a specific configuration.
   *
   * @param array            $configs   An array of configuration values
   * @param ContainerBuilder $container A ContainerBuilder instance
   *
   * @return null
   */
  public function load(array $configs, ContainerBuilder $container)
  {
    $configuration = new Configuration();
    /* $config = */$this->processConfiguration($configuration, $configs);

    $loader = new Loader\YamlFileLoader(
      $container,
      new FileLocator(__DIR__ . '/../Resources/config')
    );
    $loader->load('services.yml');
  }

}
