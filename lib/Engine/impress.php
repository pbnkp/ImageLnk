<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_impress {
  const language = 'Japanese';
  const sitename = 'http://watch.impress.co.jp/';

  public static function handle($url) {
    if (! preg_match('/^(http:\/\/([^\/]+\.)?impress\.co\.jp)(\/img\/.+).html/', $url, $matches)) {
      return FALSE;
    }

    $baseurl = $matches[1];
    $id = preg_quote(preg_replace('/\/html\//', '/', $matches[3]), '/');

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    foreach (ImageLnkHelper::scanSingleTag('img', $html) as $imgtag) {
      if (preg_match("/ src=\"({$id})\"/", $imgtag, $matches)) {
        $response->addImageURL($baseurl . $matches[1]);
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_impress');
