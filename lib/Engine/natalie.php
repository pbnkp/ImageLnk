<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_natalie {
  const language = 'Japanese';
  const sitename = 'http://natalie.mu/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/natalie\.mu\/[^\/]+\/gallery\//', $url)) {
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

    if (preg_match('/<p class="image-full">(.+?)<\/p>/s', $html, $matches)) {
      foreach (ImageLnkHelper::scanSingleTag('img', $matches[1]) as $img) {
        if (preg_match('/ src="(.+?)"/', $img, $m)) {
          $response->addImageURL('http://natalie.mu' . $m[1]);
          break;
        }
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_natalie');
