<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_itmedia {
  const language = 'Japanese';
  const sitename = 'http://www.itmedia.co.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/image\.itmedia\.co\.jp\/l\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    if (preg_match('/<h1>(.+?)<\/h1>/', $html, $matches)) {
      $response->setTitle($matches[1]);
    }
    if (preg_match('/designCnt\(\'largeImgMain\'\).*<img src="(.+?)"/', $html, $matches)) {
      $response->addImageURL($matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_itmedia');
