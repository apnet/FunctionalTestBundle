<?php

/**
 * Bundle test kernel
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */

namespace Apnet\FunctionalTestBundle\HttpKernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use JooS\Stream\Files;

/**
 * Bundle test kernel
 */
abstract class AppKernel extends Kernel
{

  /**
   * Files helper
   *
   * @var Files
   */
  private $_files = null;

  /**
   * Cache dir
   *
   * @var string
   */
  private $_cacheDir = null;

  /**
   * Logs dir
   *
   * @var string
   */
  private $_logsDir = null;

  /**
   * {@inheritdoc}
   */
  public function __construct($environment, $debug)
  {
    $this->_files = new Files();

    parent::__construct($environment, $debug);
  }

  /**
   * Kernel destructor
   */
  public function __destruct()
  {
    unset($this->_files);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheDir()
  {
    if (is_null($this->_cacheDir)) {
      $this->_cacheDir = $this->_files->mkdir();
    }
    return $this->_cacheDir;
  }

  /**
   * {@inheritdoc}
   */
  public function getLogDir()
  {
    if (is_null($this->_logsDir)) {
      $this->_logsDir = $this->_files->mkdir();
    }
    return $this->_logsDir;
  }

}
