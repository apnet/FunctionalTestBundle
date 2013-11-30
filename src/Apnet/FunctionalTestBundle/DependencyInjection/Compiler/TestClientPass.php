<?php

/**
 * Substitute test.client service with own
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Alias;

/**
 * Substitute test.client service with own
 *
 * @see Liip\FunctionalTestBundle\DependencyInjection\Compiler\SetTestClientPass
 */
class TestClientPass implements CompilerPassInterface
{

  /**
   * {@inheritdoc}
   */
  public function process(ContainerBuilder $container)
  {
    if ($container->hasDefinition('test.client')) {
      // test.client is a definition.
      // Register it again as a private service to inject it as the parent
      $definition = $container->getDefinition('test.client');
      $definition->setPublic(false);
      $container->setDefinition('apnet_ftb.webtest.client.parent', $definition);
    } elseif ($container->hasAlias('test.client')) {
      // test.client is an alias.
      // Register a private alias for this service to inject it as the parent
      $container->setAlias(
        'apnet_ftb.webtest.client.parent',
        new Alias((string) $container->getAlias('test.client'), false)
      );
    } else {
      throw new \Exception(
        'The ApnetFunctionalTestBundle can only be used in the test environment.'
      );
    }

    $container->setAlias('test.client', 'apnet_ftb.webtest.client');
  }

}
