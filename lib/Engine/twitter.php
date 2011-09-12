<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_twitter {
  const language = NULL;
  const sitename = 'http://twitter.com/';

  public static function handle($url) {
    if (! preg_match('|^https?://([^/]+)?twitter.com/.*/(\d+)/photo/(\d+)|', $url, $matches)) {
      return FALSE;
    }

    $id   = $matches[2];
    $page = $matches[3];

    // ----------------------------------------
    $url = "http://api.twitter.com/1/statuses/show.json?id={$id}&include_entities=true&contributor_details=true";

    $data = ImageLnkCache::get($url);
    $html = $data['data'];
    $info = json_decode($data['data']);

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle('twitter: ' . $info->text);
    $response->addImageURL($info->entities->media[$page - 1]->media_url);

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_twitter');
