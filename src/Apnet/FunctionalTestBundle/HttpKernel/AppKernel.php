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
  private static $files = null;

  /**
   * Cache dir
   *
   * @var string
   */
  private static $cacheDir = null;

  /**
   * Logs dir
   *
   * @var string
   */
  private static $logsDir = null;

  /**
   * {@inheritdoc}
   */
  public function getCacheDir()
  {
    if ($this->environment !== "test") {
      self::$cacheDir = parent::getCacheDir();
    } elseif (is_null(self::$cacheDir)) {
      self::$cacheDir = self::getFiles()->mkdir();
    }
    return self::$cacheDir;
  }

  /**
   * {@inheritdoc}
   */
  public function getLogDir()
  {
    if ($this->environment !== "test") {
      self::$logsDir = parent::getLogDir();
    } elseif (is_null(self::$logsDir)) {
      self::$logsDir = self::getFiles()->mkdir();
    }
    return self::$logsDir;
  }

  /**
   * Get Files helper
   *
   * @return Files
   */
  private static function getFiles()
  {
    if (is_null(self::$files)) {
      self::$files = new Files();
    }
    return self::$files;
  }
}
