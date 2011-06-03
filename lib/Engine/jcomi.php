<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_jcomi {
  const language = 'Japanese';
  const sitename = 'http://www.j-comi.net/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/www\.j-comi\.jp\/viewer\/arnoul\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    $hash = array();
    foreach(ImageLnkHelper::scanSingleTag('input', $html) as $input) {
      if (preg_match("/ id='(cr|cl)'/", $input)) {
        if (preg_match("/value='(.+?)'/", $input, $m)) {
          $imageurl = base64_decode($m[1]);
          if (! isset($hash[$imageurl])) {
            $hash[$imageurl] = true;
            $response->addImageURL($imageurl);
          }
        }
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_jcomi');
