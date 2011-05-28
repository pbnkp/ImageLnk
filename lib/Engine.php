<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine {
  private static $engines_ = array();

  public static function push($classname) {
    self::$engines_[] = $classname;
  }

  public static function getengines() {
    return self::$engines_;
  }
}
