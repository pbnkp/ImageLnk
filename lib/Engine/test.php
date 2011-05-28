<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkEngine_test {
  const sitename = NULL;

  public static function handle($url) {
    if (! preg_match('/^test:\/\/(.+)/', $url, $matches)) {
      return FALSE;
    }

    $command = $matches[1];

    // ----------------------------------------
    $response = new ImageLnkResponse();

    switch ($command) {
      case 'malformed_utf8':
        $response->setTitle(pack('C*',
                                 0xe3, 0x81, 0x82, // Japanese:あ
                                 0xe3, 0x81, 0x84, // Japanese:い
                                 0xe3, 0x81, 0x86, // Japanese:う
                                 0xe3, 0x81, 0x88, // Japanese:え
                                 0xe3, 0x81, 0x8a, // Japanese:お
                                 0xe3, 0x81,       // broken
                                 0xe3, 0x81, 0x8b, // Japanese:か
                                 0xe3, 0x81, 0x8d  // Japanese:き
                              ));
        $response->addImageURL('malformed_utf8');
        break;
    }

    return $response;
  }
}
ImageLnkEngine::push('ImageLnkEngine_test');
