<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_twitpic {
  const language = NULL;
  const sitename = 'http://twitpic.com/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/twitpic\.com\/+([^\/]+)/', $url, $matches)) {
      return FALSE;
    }

    $id = $matches[1];

    // ----------------------------------------
    if (! preg_match('/\/full$/', $url)) {
      $url = "http://twitpic.com/$id/full";
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    foreach (ImageLnkHelper::scanSingleTag('img', $html) as $img) {
      if (preg_match('% src="(.+?/full/.+?)"%s', $img, $m)) {
        $response->addImageURL($m[1]);
        if (preg_match('/alt="(.+?)"/s', $img, $m)) {
          $response->setTitle($m[1]);
        }
        break;
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_twitpic');
