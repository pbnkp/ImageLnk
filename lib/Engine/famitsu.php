<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_famitsu {
  const language = 'Japanese';
  const sitename = 'http://www.famitsu.com/';

  public static function handle($url) {
    if (! preg_match('/^(http:\/\/www\.famitsu\.com\/news\/\d+\/images\/\d+\/)(.+\.)html$/', $url, $matches)) {
      return FALSE;
    }

    $baseurl = $matches[1];
    $id = preg_quote($matches[2], '/');

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));
    if (preg_match("/ src=\"($id.+?)\"/", $html, $matches)) {
      $response->addImageURL($baseurl . $matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_famitsu');
