<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_niconico {
  const language = NULL;
  const sitename = 'http://www.niconico.com/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/video\.niconico\.com\/watch\//', $url)) {
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
ImageLnkEngine::push('ImageLnkEngine_niconico');
