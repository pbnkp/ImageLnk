<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_impress {
  const language = 'Japanese';
  const sitename = 'http://watch.impress.co.jp/';

  public static function handle_common($url) {
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

  public static function handle_akiba($url) {
    if (! preg_match('|^(http://akiba-pc.watch.impress.co.jp/hotline/.+?/image/)|', $url, $matches)) {
      return FALSE;
    }

    $baseurl = $matches[1];

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("SHIFT_JIS", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    if (preg_match('|<!--image-->(.+?)<!--/image-->|', $html, $matches)) {
      foreach (ImageLnkHelper::scanSingleTag('img', $matches[1]) as $imgtag) {
        if (preg_match('/ src="(.+?)"/', $imgtag, $m)) {
          $response->addImageURL($baseurl . $m[1]);
        }
      }
    }

    return $response;
  }

  public static function handle($url) {
    $response = self::handle_common($url);

    if ($response === FALSE) {
      $response = self::handle_akiba($url);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_impress');
