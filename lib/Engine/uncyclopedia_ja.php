<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_uncyclopedia_ja {
  const language = 'Japanese';
  const sitename = 'http://ja.uncyclopedia.info/';

  public static function handle($url) {
    if (! preg_match('|^http://ja.uncyclopedia.info/wiki/.+|', $url, $matches) &&
        ! preg_match('|^http://ansaikuropedia.org/wiki/.+|', $url, $matches)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    if (preg_match('/ class="fullImageLink".+?href="(.+?)"/', $html, $matches)) {
      $response->addImageURL($matches[1]);

      if (preg_match('/id="fileinfotpl_desc".+?<td>(.*?)<\/td>/s', $html, $matches)) {
        $response->setTitle(preg_replace('/\s+/', ' ', strip_tags(trim($matches[1]))));
      }
    }

    if (! $response->getTitle()) {
      // fall-back
      $title = ImageLnkHelper::getTitle($html);
      if ($title) {
        $response->setTitle($title);
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_uncyclopedia_ja');
