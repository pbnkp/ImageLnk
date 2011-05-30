<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_photozou {
  const language = 'Japanese';
  const sitename = 'http://photozou.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/photozou\.jp\/photo\/[^\/]+?\/(\d+)\/(\d+)/', $url, $matches)) {
      return FALSE;
    }

    $id1 = $matches[1];
    $id2 = $matches[2];

    $url = "http://photozou.jp/photo/photo_only/{$id1}/{$id2}";

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/<a href="(.+?)">この写真をダウンロードする<\/a>/', $html, $matches)) {
      $response->addImageURL($matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_photozou');
