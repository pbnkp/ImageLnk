<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_picasa {
  const sitename = 'http://picasa.google.com/';

  public static function handle($url) {
    if (! preg_match('/^https?:\/\/picasaweb\.google\.com\/.+#(\d+)$/', $url, $matches)) {
      return FALSE;
    }

    $id = $matches[1];

    // Use http because we cannot connect to https using HTTP_Request2.
    $url = preg_replace('/^https/', 'http', $url);

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    if (preg_match(sprintf('/"gphoto\$id":"%s".+?"url":"(.+?)"/s', $id), $html, $matches)) {
      $response->addImageURL($matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_picasa');
