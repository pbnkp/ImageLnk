<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_zakzak {
  const language = 'Japanese';
  const sitename = 'http://www.zakzak.co.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/www\.zakzak\.co\.jp\/.+\/photos\/.+\.htm$/', $url, $matches)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/<table class="photo">(.+?)<\/table>/s', $html, $matches)) {
      foreach (ImageLnkHelper::scanSingleTag('img', $matches[1]) as $img) {
        if (preg_match('/ src="[\.\/]+(.+?)"/s', $html, $m)) {
          $response->addImageURL('http://www.zakzak.co.jp/' . $m[1]);
        }
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_zakzak');
