<?php

use Apnet\FunctionalTestBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends FunctionalTestBundle\HttpKernel\AppKernel
{

  /**
   * {@inheritdoc}
   */
  public function registerBundles()
  {
    return array(
      new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
      new Symfony\Bundle\MonologBundle\MonologBundle(),
      new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
      new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

      new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
      new FunctionalTestBundle\ApnetFunctionalTestBundle()
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
