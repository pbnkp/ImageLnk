<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_tumblr {
  const language = NULL;
  const sitename = 'http://www.tumblr.com/';

  public static function handle($url) {
    if (! preg_match('/^(http:\/\/[^\/]+\.tumblr\.com\/)post\/(\d+)/', $url, $matches)) {
      return FALSE;
    }

    $baseurl = $matches[1];
    $id = $matches[2];

    $apiurl = $baseurl . 'api/read?id=' . $id;

    // ----------------------------------------
    $data = ImageLnkCache::get($apiurl);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    if (preg_match('/<tumblelog .*? title="(.+?)"/', $html, $matches)) {
      $response->setTitle($matches[1]);
    }
    if (preg_match('/<photo-caption>(.+?)<\/photo-caption>/', $html, $matches)) {
      $response->setTitle($response->getTitle() . ': ' . strip_tags(html_entity_decode($matches[1])));
    }

    if (preg_match('/<photo-url .*?>(.+?)<\/photo-url>/', $html, $matches)) {
      $response->addImageURL($matches[1]);
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_tumblr');
