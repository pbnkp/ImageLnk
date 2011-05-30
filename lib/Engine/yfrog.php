<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_yfrog {
  const language = NULL;
  const sitename = 'http://yfrog.com/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/yfrog\.com\//', $url)) {
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
ImageLnkEngine::push('ImageLnkEngine_yfrog');
