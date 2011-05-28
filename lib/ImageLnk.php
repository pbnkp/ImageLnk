<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

error_reporting(E_ALL | E_STRICT);

require_once 'HTTP/Request2.php';
require_once 'SymfonyComponents/YAML/sfYaml.php';

foreach (glob(sprintf('%s/*.php', dirname(__FILE__))) as $file) {
  require_once $file;
}

ImageLnkConfig::static_initialize();

// ------------------------------------------------------------
foreach (glob(sprintf('%s/Engine/*.php', dirname(__FILE__))) as $file) {
  require_once $file;
}

// ------------------------------------------------------------
class ImageLnk {
  public static function getImageInfo($url) {
    foreach (ImageLnkEngine::getEngines() as $classname) {
      try {
        $response = $classname::handle($url);
        if ($response !== FALSE) {
          return $response;
        }
      } catch (Exception $e) {
        error_log('getImageInfo got Exception: ' . $e->getMessage());
        error_log($e->getTraceAsString());
      }
    }
    return FALSE;
  }

  public static function getSites() {
    $sites = array();
    foreach (ImageLnkEngine::getEngines() as $classname) {
      if ($classname::sitename) {
        $sites[] = array('name' => $classname::sitename);
      }
    }
    sort($sites);
    return $sites;
  }
}
