<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_dengeki {
  const language = 'Japanese';
  const sitename = 'http://news.dengeki.com/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/news\.dengeki\.com(\/.+?\/)img.html/', $url, $matches)) {
      return FALSE;
    }

    $id = preg_quote($matches[1], '/');

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    if (preg_match("/src=\"({$id}.*?)\"/", $html, $matches)) {
      $response->addImageURL('http://news.dengeki.com' . $matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_dengeki');
