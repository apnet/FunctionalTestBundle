<?php

/**
 * Client simulates a browser and makes requests to a Kernel object.
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */
namespace Apnet\FunctionalTestBundle\Framework;

use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Client as BaseClient;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Routing\RouteCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Client simulates a browser and makes requests to a Kernel object.
 */
class Client extends BaseClient
{

  /**
   * Data fixtures executor
   *
   * @var ORMExecutor
   */
  private $executor;

  /**
   * Empty database file contents
   *
   * @var string
   */
  private static $dbBackup = null;

  /**
   * Create empty database
   *
   * @return null
   * @throws \Exception when path to test sqlite database is not configured
   */
  public function setUp()
  {
    $container = $this->getContainer();
    if (!$container->has('doctrine')) {
      return;
    }
    $manager = $container->get('doctrine')
      ->getManager();
    /* @var $manager EntityManager */
    $connection = $manager->getConnection();
    $parameters = $connection->getParams();

    if (isset($parameters["path"])) {
      $dbPath = $parameters["path"];

      if (!is_null(self::$dbBackup)) {
        $connection->close();
        file_put_contents($dbPath, self::$dbBackup);
        $connection->connect();
      } else {
        $metadata = $manager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($manager);
        $schemaTool->dropDatabase();
        if (!empty($metadata)) {
          $schemaTool->createSchema($metadata);
        }

        $connection->close();
        self::$dbBackup = file_get_contents($dbPath);
        $connection->connect();
      }
    } else {
      throw new \Exception("'doctrine.dbal.path' is not configired");
    }
  }

  /**
   * Tear down
   *
   * @return null
   */
  public function tearDown()
  {
    $container = $this->getContainer();
    if ($container->has('doctrine')) {
      $manager = $container->get('doctrine')
        ->getManager();
      /* @var $manager EntityManager */
      $manager->getConnection()->close();
    }
    $this->executor = null;
  }

  /**
   * Set the database to the provided fixtures.
   *
   * @param AbstractFixture[] $fixtures Fixtures class names
   *
   * @return null
   */
  public function loadFixtures(array $fixtures)
  {
    $container = $this->getContainer();
    if (is_null($this->executor)) {
      $manager = $container->get('doctrine')->getManager();
      /* @var $manager EntityManager */
      $referenceRepository = new ProxyReferenceRepository($manager);
      $this->executor = new ORMExecutor($manager);
      $this->executor->setReferenceRepository($referenceRepository);
    }

    $loader = new ContainerAwareLoader($container);
    foreach ($fixtures as $fixture) {
      if (!$loader->hasFixture($fixture)) {
        $loader->addFixture($fixture);
      }
    }
    /* @var $loader Loader */
    $this->executor->execute(
      $loader->getFixtures(), true
    );
  }

  /**
   * Check if route exists
   *
   * @param string $name Route name
   *
   * @return boolean
   */
  public function routeExists($name)
  {
    $collection = $this->getContainer()
      ->get('router')
      ->getRouteCollection();
    /* @var $collection RouteCollection */
    return !!$collection->get($name);
  }

  /**
   * Run command
   *
   * @param string $name   Command name
   * @param array  $params Parameters
   *
   * @return string
   */
  public function runCommand($name, array $params = array())
  {
    array_unshift($params, $name);

    $kernel = $this->getKernel();

    $application = new Application($kernel);
    $application->setAutoExit(false);

    $input = new ArrayInput($params);
    $input->setInteractive(false);

    $filePointer = fopen('php://temp/maxmemory:5242880', 'r+');
    $output = new StreamOutput($filePointer);

    $application->run($input, $output);

    rewind($filePointer);
    return stream_get_contents($filePointer);
  }
}
