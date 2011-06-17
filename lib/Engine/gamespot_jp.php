<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_gamespot_jp {
  const language = 'Japanese';
  const sitename = 'http://japan.gamespot.com/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/japan\.gamespot\.com\/image\//', $url, $matches)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/<div class="screenshot center">(.+?)<\/div>/s', $html, $matches)) {
      foreach (ImageLnkHelper::scanSingleTag('img', $matches[1]) as $imgtag) {
        if (preg_match('/ src="(.+?)"/', $imgtag, $m)) {
          $response->addImageURL('http://japan.gamespot.com' . $m[1]);
        }
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_gamespot_jp');
