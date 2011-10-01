<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_cnet_jp {
  const language = 'Japanese';
  const sitename = 'http://japan.cnet.com/';

  public static function handle($url) {
    if (! preg_match('%^http://japan\.cnet\.com/%', $url)) {
      return FALSE;
    }

    // ----------------------------------------
    $data = ImageLnkCache::get($url);
    $html = $data['data'];

    $response = new ImageLnkResponse();
    $response->setReferer($url);

    $response->setTitle(ImageLnkHelper::getTitle($html));

    $matches = NULL;
    if (! preg_match('%<div class="story_photoreport">(.+)%s', $html, $matches)) {
      return NULL;
    }

    foreach (ImageLnkHelper::scanSingleTag('img', $matches[1]) as $img) {
      if (preg_match('/ src="(.+?)"/', $img, $m)) {
        $response->addImageURL('http://japan.cnet.com' . $m[1]);
      }
      if (preg_match('/ alt="(.+?)"/', $img, $m)) {
        $response->setTitle($response->getTitle() . ': ' . $m[1]);
      }
      break;
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_cnet_jp');
