<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_nicovideo {
  const sitename = 'http://www.nicovideo.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/www\.nicovideo\.jp\/watch\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $title = ImageLnkHelper::getTitle($html);
    if ($title !== FALSE) {
      $response->setTitle($title);
    }

    foreach (ImageLnkHelper::scanSingleTag('img', $html) as $img) {
      if (preg_match('/ src="(http:\/\/tn-skr\d+\.smilevideo\.jp\/smile\?i=\d+)" /', $img, $m)) {
        $response->addImageURL($m[1]);
        break;
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_nicovideo');
