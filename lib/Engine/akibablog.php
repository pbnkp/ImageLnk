<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_akibablog {
  const sitename = 'http://blog.livedoor.jp/geek/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/node.*?\.img.*?\.akibablog\.net\/.*\.html$/', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/src="\.\/(.+?)"/', $html, $matches)) {
      $response->addImageURL(dirname($url) . '/' . $matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_akibablog');
