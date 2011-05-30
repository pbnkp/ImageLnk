<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_wikipedia {
  const language = NULL;
  const sitename = 'http://www.wikipedia.org/';

  public static function handle($url) {
    if (! preg_match('/^http:\/\/[^\/]+\.wikipedia\.org\/wiki\/.+/', $url, $matches)) {
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
        $response->setTitle(strip_tags(trim($matches[1])));
      }
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_wikipedia');
