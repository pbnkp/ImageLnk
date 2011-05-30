<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_lockerz {
  const language = NULL;
  const sitename = 'http://lockerz.com/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/lockerz.com\/s\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    ImageLnkHelper::setResponseFromOpenGraph($response, $html);

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_lockerz');
