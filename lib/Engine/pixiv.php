<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_pixiv {
  const language = NULL;
  const sitename = 'http://www.pixiv.net/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/www\.pixiv\.net\/member_illust\.php/', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    if (preg_match('/member_illust\.php\?mode=(manga_big|big)/', $url)) {
      $response->setTitle(ImageLnkHelper::getTitle($html));
      foreach (ImageLnkHelper::scanSingleTag('img', $html) as $img) {
        if (preg_match('/src="(.+?)"/', $img, $m)) {
          $response->addImageURL($m[1]);
        }
      }

    } elseif (preg_match('/member_illust\.php\?mode=manga/', $url)) {
      $response->setTitle(ImageLnkHelper::getTitle($html));
      if (preg_match_all("/unshift\('(.+?)'/", $html, $m)) {
        foreach ($m[1] as $imgsrc) {
          $response->addImageURL($imgsrc);
        }
      }

    } else {
      ImageLnkHelper::setResponseFromOpenGraph($response, $html);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_pixiv');
