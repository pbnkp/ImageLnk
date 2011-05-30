<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_hatena {
  const language = 'Japanese';
  const sitename = 'http://f.hatena.ne.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/f\.hatena\.ne\.jp\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    foreach (ImageLnkHelper::scanSingleTag('img', $html) as $img) {
      if (preg_match('/ class="foto"/', $img)) {
        if (preg_match('/src="(.+?)"/', $img, $m)) {
          $response->addImageURL($m[1]);
        }
        if (preg_match('/title="(.+?)"/', $img, $m)) {
          $response->setTitle($m[1]);
        }
        break;
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_hatena');
