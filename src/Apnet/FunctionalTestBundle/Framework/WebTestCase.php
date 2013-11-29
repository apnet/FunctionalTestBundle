<?php

/**
 * WebTestCase
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link https://github.com/liip/LiipFunctionalTestBundle
 */
namespace Apnet\FunctionalTestBundle\Framework;

use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * WebTestCase is the base class for functional tests.
 */
abstract class WebTestCase extends BaseWebTestCase
{

  /**
   * Default Client object
   *
   * @var Client
   */
  private $_client = null;

  /**
   * Data fixtures executor
   *
   * @var ORMExecutor
   */
  private $_executor;

  /**
   * Creates default instance of a lightweight Http client.
   *
   * @param array $authentication Authentification
   * @param array $params         Params
   *
   * @return Client
   */
  public function setupClient(array $authentication = null, array $params = array())
  {
    if (is_null($this->_client)) {
      $this->_client = $this->newClient($authentication, $params);
      $this->setDefaultClientUp($this->_client);
    }
    return $this->_client;
  }

  /**
   * Creates an instance of a lightweight Http client.
   *
   * $params can be used to pass headers to the client, note that they have
   * to follow the naming format used in $_SERVER.
   * Example: 'HTTP_X_REQUESTED_WITH' instead of 'X-Requested-With'
   *
   * @param array $authentication Array('username' => ..., 'password' => ...)
   * @param array $params         An array of server parameters
   *
   * @return Client
   * @throws \InvalidArgumentException
   */
  public function newClient(array $authentication = null, array $params = array())
  {
    if ($authentication) {
      $params = array_merge(
        $params, array(
          'PHP_AUTH_USER' => $authentication['username'],
          'PHP_AUTH_PW'   => $authentication['password']
        )
      );
    }

    $client = static::createClient(array('environment' => 'test'), $params);

    $this->setNewClientUp($client);
    return $client;
  }

  /**
   * Get initialized client
   *
   * @param boolean $check Check for not initialized client
   *
   * @return Client|null
   * @throws \Exception
   */
  public function getClient($check = false)
  {
    if ($check) {
      if (is_null($this->_client)) {
        throw new \Exception(
          "Use \$this->setupClient(...) to create a client first"
        );
      }
    }
    return $this->_client;
  }

  /**
   * Run command
   *
   * @param string $name   Command name
   * @param array  $params Parameters
   * @param Client $client Client
   *
   * @return string
   */
  public function runCommand($name, array $params = array(), Client $client = null)
  {
    array_unshift($params, $name);

    if (is_null($client)) {
      $client = $this->getClient(true);
    }
    $kernel = $client->getKernel();

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

  /**
   * Get di container
   *
   * @param Client $client Client
   *
   * @return ContainerInterface
   */
  public function getContainer(Client $client = null)
  {
    if (is_null($client)) {
      $client = $this->getClient(true);
    }
    return $client->getContainer();
  }

  /**
   * Get 'doctrine' service
   *
   * @param Client $client Client
   *
   * @return Registry
   */
  public function getDoctrine(Client $client = null)
  {
    if (is_null($client)) {
      $client = $this->getClient(true);
    }
    return $this->getContainer($client)->get('doctrine');
  }

  /**
   * Get Router route collection
   *
   * @param Client $client Client
   *
   * @return RouteCollection
   */
  public function getRouteCollection(Client $client = null)
  {
    if (is_null($client)) {
      $client = $this->getClient(true);
    }
    return $client->getContainer()->get('router')->getRouteCollection();
  }

  /**
   * Assert route exists
   *
   * @param string $name Route name
   *
   * @return null
   */
  public function assertRouteExists($name)
  {
    $this->assertNotNull(
      $this->getRouteCollection()->get($name),
      "Route '$name' must exist"
    );
  }

  /**
   * Set the database to the provided fixtures.
   *
   * @param AbstractFixture[] $fixtures Fixtures class names
   * @param Client            $client   Client
   *
   * @return null
   */
  public function loadFixtures(array $fixtures, Client $client = null)
  {
    if (is_null($client)) {
      $client = $this->getClient(true);
    }

    if (is_null($this->_executor)) {
      $manager = $this->getDoctrine()->getManager();
      /* @var $manager EntityManager */
      $referenceRepository = new ProxyReferenceRepository($manager);
      $this->_executor = new ORMExecutor($manager);
      $this->_executor->setReferenceRepository($referenceRepository);
    }

    $loader = new ContainerAwareLoader($client->getContainer());
    foreach ($fixtures as $fixture) {
      if (!$loader->hasFixture($fixture)) {
        $loader->addFixture($fixture);
      }
    }
    /* @var $loader Loader */
    $this->_executor->execute(
      $loader->getFixtures(), true
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown()
  {
    if (!is_null($this->_client)) {
      $this->tearDefaultClientDown($this->_client);
    }
    parent::tearDown();
    $this->_client = null;
  }

  /**
   * Sets up new Client
   *
   * This method is called after new Client is created.
   *
   * @param Client $client Client
   *
   * @return null
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  protected function setNewClientUp(Client $client)
  {

  }

  /**
   * Sets up default Client
   *
   * This method is called after Client is created.
   *
   * @param Client $client Client
   *
   * @return null
   */
  protected function setDefaultClientUp(Client $client)
  {
    static $backupTime = 0;

    $manager = $this->getDoctrine($client)->getManager();
    /* @var $manager EntityManager */
    $connection = $manager->getConnection();
    $parameters = $connection->getParams();

    $dbPath = null;
    $backupPath = null;
    $backupExists = false;
    if (isset($parameters["path"])) {
      $dbPath = $parameters["path"];
      $backupPath = $dbPath . ".backup";
      if (file_exists($backupPath)) {
        $backupTime = filemtime($backupPath);
        if ($backupTime == $backupTime) {
          $backupExists = true;
        }
      }
    }

    if ($backupExists) {
      $connection->close();
      @unlink($dbPath);
      copy($backupPath, $dbPath);
      $connection->connect();
    } else {
      $metadata = $manager->getMetadataFactory()->getAllMetadata();

      $schemaTool = new SchemaTool($manager);
      $schemaTool->dropDatabase();
      if (!empty($metadata)) {
        $schemaTool->createSchema($metadata);
      }

      $connection->close();
      @unlink($backupPath);
      copy($dbPath, $backupPath);
      $backupTime = filemtime($dbPath);
      $connection->connect();
    }
  }

  /**
   * Tears down the fixture, for example, close a network connection.
   * This method is called before client & kernel destroyed
   *
   * @param Client $client Client
   *
   * @return null
   * @SuppressWarnings(PHPMD.UnusedFormalParameter)
   */
  protected function tearDefaultClientDown(Client $client)
  {
    $this->_executor = null;
  }

}
