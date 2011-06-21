<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_pixiv {
  const language = NULL;
  const sitename = 'http://www.pixiv.net/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/(www|touch)\.pixiv\.net\/member_illust\.php/', $url)) {
      return FALSE;
    }

    $url = preg_replace('/^http:\/\/touch\.pixiv\.net/', 'http://www.pixiv.net', $url);

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    // --------------------
    // If mode=medium, fetch large image page
    if (preg_match('/\?mode=medium&/', $url)) {
      if (preg_match('/<div class="works_display">(.*?)<\/div>/s', $html, $matches)) {
        if (preg_match('/ href="(member_illust.php\?mode=big&.*?)"/', $matches[1], $m)) {
          $newurl = 'http://www.pixiv.net/' . html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
          return self::handle($newurl);
        }
        if (preg_match('/ href="(member_illust.php\?mode=manga&.*?)"/', $matches[1], $m)) {
          $newurl = preg_replace('/mode=manga&/', 'mode=manga_big&', $m[1]);
          $newurl = 'http://www.pixiv.net/' . html_entity_decode($newurl, ENT_QUOTES, 'UTF-8') . '&page=0';
          return self::handle($newurl);
        }
      }
    }

    // --------------------
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
