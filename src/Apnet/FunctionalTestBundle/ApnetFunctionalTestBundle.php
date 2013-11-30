<?php

/**
 * Apnet Functional Test Bundle
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Apnet Functional Test Bundle
 */
class ApnetFunctionalTestBundle extends Bundle
{

  /**
   * {@inheritdoc}
   */
  public function build(ContainerBuilder $container)
  {
    $container->addCompilerPass(
      new DependencyInjection\Compiler\TestClientPass()
    );
  }

}
