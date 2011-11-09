<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_mynavi {
  const language = 'Japanese';
  const sitename = 'http://news.mynavi.jp/';

  public static function handle($url) {
    if (! preg_match('%http://news\.mynavi\.jp/photo/%', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    if (preg_match_all('%<a (.+?)>(.+?)</a>%s', $html, $matches)) {
      foreach ($matches[1] as $k => $a) {
        if (preg_match('%id="photo-link"%', $a)) {
          if (preg_match('% src="(.+?)"%', $matches[2][$k], $m)) {
            $response->addImageURL('http://news.mynavi.jp' . $m[1]);
            break;
          }
        }
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_mynavi');
