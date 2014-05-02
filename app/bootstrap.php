<?php

use Symfony\Component\Debug\Debug;
use Doctrine\Common\Annotations\AnnotationRegistry;

if (!file_exists(dirname(__DIR__) . "/vendor/autoload.php")) {
  echo
    'You need to set up the project dependencies using the following commands:' . PHP_EOL .
    'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
    'php composer.phar install' . PHP_EOL;
} else {
  $loader = include __DIR__ . "/../vendor/autoload.php";

  $loader->add("Apnet\\Dev\\", __DIR__ . "/src");
  $loader->add("Apnet\\TestEntityBundle\\", __DIR__ . "/src");
  $loader->register();

  AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

  chdir(dirname(__DIR__));
  Debug::enable();
}
