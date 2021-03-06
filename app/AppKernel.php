<?php

use Apnet\FunctionalTestBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

// @codingStandardsIgnoreStart
class AppKernel extends FunctionalTestBundle\HttpKernel\AppKernel
// @codingStandardsIgnoreEnd
{

  /**
   * {@inheritdoc}
   */
  public function registerBundles()
  {
    return array(
      new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
      new Symfony\Bundle\TwigBundle\TwigBundle(),
      new Symfony\Bundle\MonologBundle\MonologBundle(),
      new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
      new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

      new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
      new FunctionalTestBundle\ApnetFunctionalTestBundle(),

      new Apnet\TestEntityBundle\ApnetTestEntityBundle(),
      new Covex\TwigCallableBridgeBundle\CovexTwigCallableBridgeBundle(),
      new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function registerContainerConfiguration(LoaderInterface $loader)
  {
    $loader->load(__DIR__ . "/config/config.yml");
  }
}
