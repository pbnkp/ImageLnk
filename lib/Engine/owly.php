<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_owly {
  const sitename = 'http://ow.ly/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/ow\.ly\/i\/+([^\/]+)/', $url, $matches)) {
      return FALSE;
    }

    $id = preg_quote($matches[1], '/');

    // ----------------------------------------
    if (! preg_match('/\/original$/', $url)) {
      $url = "http://ow.ly/i/{$id}/original";
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    foreach (ImageLnkHelper::scanSingleTag('img', $html) as $img) {
      if (preg_match(sprintf('/src="(http:\/\/static\.ow\.ly\/photos\/original\/%s.*?)"/s', $id), $img, $m)) {
        $response->addImageURL($m[1]);
        if (preg_match('/alt="(.+?)"/s', $img, $m)) {
          $response->setTitle($response->getTitle() . ': ' . $m[1]);
        }
        break;
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_owly');
