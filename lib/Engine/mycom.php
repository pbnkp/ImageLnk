<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_mycom {
  const sitename = 'http://journal.mycom.co.jp/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/journal\.mycom\.co\.jp\/photo\//', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match('/<a id="photo-link".*?><img src="(.+?)"/', $html, $matches)) {
      $response->addImageURL($matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_mycom');
