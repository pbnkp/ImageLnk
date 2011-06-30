<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkCache {
  public static function getCacheDirectory() {
    return sprintf('%s/%s', ImageLnkConfig::v('cache_directory'), sha1(__FILE__));
  }

  public static function getCacheFilePathFromURL($url) {
    $hash = sha1($url);

    $path = self::getCacheDirectory() . '/page/';

    for ($i = 0; $i < 4; ++$i) {
      $path .= substr($hash, $i, 1) . '/';
    }

    $path .= $hash;
    return $path;
  }

  public static function writeToCacheFile($path, $data) {
    $directory = dirname($path);

    if (! is_dir($directory)) {
      if (mkdir($directory, 0700, true) === FALSE) {
        throw new ImageLnkException();
      }
    }

    $outfile = tempnam($directory, 'ImageLnk');
    if ($outfile === FALSE) {
      throw new ImageLnkException();
    }
    if (file_put_contents($outfile, $data) === FALSE) {
      throw new ImageLnkException();
    }
    if (rename($outfile, $path) === FALSE) {
      throw new ImageLnkException();
    }
  }

  public static function readFromCacheFile($path) {
    if (! is_file($path)) {
      return FALSE;
    }
    if (time() - filemtime($path) > 60 * ImageLnkConfig::v('cache_expire_minutes')) {
      return FALSE;
    }
    return file_get_contents($path);
  }

  public static function get($url, $referer = NULL) {
    $path = self::getCacheFilePathFromURL($url);

    $data = array(
      'data' => self::readFromCacheFile($path),
      'from_cache' => true,
      );

    if ($data['data'] === FALSE) {
      $data['from_cache'] = false;
      $data['data'] = ImageLnkFetcher::fetch($url, $referer);

      self::writeToCacheFile($path, $data['data']);
    }

    return $data;
  }
}
