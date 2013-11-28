<?php

/**
 * Abstract single object data fixture
 */

namespace Apnet\FunctionalTestBundle\Framework\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract single object data fixture
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 */
abstract class SingleObjectFixture extends AbstractFixture
  implements ContainerAwareInterface
{

  /**
   * Object reference
   *
   * @var string
   */
  public $objectRef;

  /**
   * Create new database entity
   *
   * @return mixed
   */
  abstract function createObject();

  /**
   * {@inheritdoc}
   */
  final public function load(ObjectManager $manager)
  {
    $object = $this->createObject();

    $manager->persist($object);
    $manager->flush();

    $this->addReference($this->objectRef, $object);
  }

  /**
   * DI container
   *
   * @var ContainerInterface
   */
  private $_container;

  /**
   * {@inheritdoc}
   */
  public function setContainer(ContainerInterface $container = null)
  {
    $this->_container = $container;
  }

  /**
   * Get di container
   *
   * @return ContainerInterface
   */
  public function getContainer()
  {
    return $this->_container;
  }

}
