<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_news_livedoor_com {
  const language = 'Japanese';
  const sitename = 'http://news.livedoor.com/';

  public static function handle($url) {
    if (! preg_match('%http://news\.livedoor\.com/article/image_detail/%', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $html = @iconv("EUC-JP", "UTF-8//IGNORE", $html);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    if (preg_match('%<div id="photo-detail">(.+?)</div>%', $html, $matches)) {
      foreach (ImageLnkHelper::scanSingleTag('img', $matches[1]) as $img) {
        if (preg_match('/ src="(.+?)"/', $img, $m)) {
          $response->addImageURL($m[1]);
          break;
        }
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_news_livedoor_com');
