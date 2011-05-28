<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_ascii {
  const sitename = 'http://ascii.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/ascii\.jp(\/elem\/.*?\/)img.html$/', $url, $matches)) {
      return FALSE;
    }

    $id = preg_quote($matches[1], '/');

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/<ul id="rellist" >.*?<a .*?>(.+?)<\/a>/s', $html, $matches)) {
      $response->setTitle($matches[1]);
    }

    if (preg_match("/src=\"({$id}.*?)\"/", $html, $matches)) {
      $response->addImageURL('http://ascii.jp' . $matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_ascii');
