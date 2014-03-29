<?php

/**
 * Bundle test kernel
 *
 * @author Andrey F. Mindubaev <covex.mobile@gmail.com>
 * @license http://opensource.org/licenses/MIT  MIT License
 */

namespace Apnet\FunctionalTestBundle\HttpKernel;

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
  private static $_files = null;

  /**
   * Cache dir
   *
   * @var string
   */
  private static $_cacheDir = null;

  /**
   * Logs dir
   *
   * @var string
   */
  private static $_logsDir = null;

  /**
   * {@inheritdoc}
   */
  public function getCacheDir()
  {
    if (is_null(self::$_cacheDir)) {
      self::$_cacheDir = self::_getFiles()->mkdir();
    }
    return self::$_cacheDir;
  }

  /**
   * {@inheritdoc}
   */
  public function getLogDir()
  {
    if (is_null(self::$_logsDir)) {
      self::$_logsDir = self::_getFiles()->mkdir();
    }
    return self::$_logsDir;
  }

  /**
   * Get Files helper
   *
   * @return Files
   */
  private static function _getFiles()
  {
    if (is_null(self::$_files)) {
      self::$_files = new Files();
    }
    return self::$_files;
  }

}
