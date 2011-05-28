<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_yaplog {
  const sitename = 'http://www.yaplog.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/yaplog\.jp\/.+\/image\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/<h1 class="imgMain">.*?<img src="(.+?)"/s', $html, $matches)) {
      $response->addImageURL($matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_yaplog');
