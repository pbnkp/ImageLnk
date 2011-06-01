<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

require_once 'PHPUnit/Framework.php';
require_once sprintf('%s/../lib/ImageLnk.php', dirname(__FILE__));

class ImageLnkTest extends PHPUnit_Framework_TestCase {
  function __construct() {
    ImageLnkConfig::set('cache_directory', 'tmp');
    ImageLnkConfig::set('cache_expire_minutes', 30);
  }

  private function check_response($url, $title, $imageurls, $referer = NULL) {
    $response = ImageLnk::getImageInfo($url);

    $expect = $title;
    $actual = $response->getTitle();
    $this->assertSame($expect, $actual);

    $expect = $imageurls;
    $actual = $response->getImageURLs();
    $this->assertSame($expect, $actual);

    if ($referer == NULL) {
      $referer = $url;
    }
    $expect = $referer;
    $actual = $response->getReferer();
    $this->assertSame($expect, $actual);
  }

  // ======================================================================
  function test_test1() {
    $url = 'test://malformed_utf8';
    $title = 'あいうえおかき';
    $imageurls = array(
      'malformed_utf8',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_ameblo1() {
    $url = 'http://ameblo.jp/hakasetoiu-ikimono/image-10430643614-10370336976.html';
    $response = ImageLnk::getImageInfo($url);

    $title = '第４話：Beautiful nameの画像 | 研究者マンガ「ハカセといふ生物」';
    $actual = $response->getTitle();
    $this->assertSame($title, $actual);

    $referer = $url;
    $actual = $response->getReferer();
    $this->assertSame($referer, $actual);

    foreach ($response->getImageURLs() as $imageurl) {
      $expect = 1;
      $actual = preg_match('/http:\/\/stat.*\.ameba\.jp\/user_images\/20100109\/22\/hakasetoiu-ikimono\/5f\/c7\/j\/o0360050010370336976\.jpg/', $imageurl);
      $this->assertSame($expect, $actual);
    }
  }

  function test_ameblo2() {
    $url = 'http://s.ameblo.jp/hakasetoiu-ikimono/image-10430643614-10370336976.html';
    $response = ImageLnk::getImageInfo($url);

    $title = '第４話：Beautiful nameの画像 | 研究者マンガ「ハカセといふ生物」';
    $actual = $response->getTitle();
    $this->assertSame($title, $actual);

    $referer = $url;
    $actual = $response->getReferer();
    $this->assertSame($referer, $actual);

    foreach ($response->getImageURLs() as $imageurl) {
      $expect = 1;
      $actual = preg_match('/http:\/\/stat.*\.ameba\.jp\/user_images\/20100109\/22\/hakasetoiu-ikimono\/5f\/c7\/j\/o0360050010370336976\.jpg/', $imageurl);
      $this->assertSame($expect, $actual);
    }
  }

  // ======================================================================
  function test_ascii1() {
    $url = 'http://ascii.jp/elem/000/000/581/581329/img.html';
    $title = 'ジャストシステム、Office互換ソフト市場に参入';
    $imageurls = array(
      'http://ascii.jp/elem/000/000/581/581329/06_c_800x531.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_akibablog1() {
    $url = 'http://node3.img3.akibablog.net/11/may/1/real-qb/119.html';
    $title = '[画像]:ゲーマーズ本店にリアルキュゥべえ　「どうみても不審者ｗｗｗ」';
    $imageurls = array(
      'http://node3.img3.akibablog.net/11/may/1/real-qb/119.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_hatena1() {
    $url = 'http://f.hatena.ne.jp/tekezo/20090625215759';
    $title = 'タイトルです。';
    $imageurls = array(
      'http://cdn-ak.f.st-hatena.com/images/fotolife/t/tekezo/20090625/20090625215759.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_mycom1() {
    $url = 'http://journal.mycom.co.jp/photo/articles/2011/03/07/appinventor/images/006l.jpg';
    $title = '拡大画像 006 | 【ハウツー】経験ゼロでも大丈夫!? App Inventorで始めるAndroidアプリ開…… | マイコミジャーナル';
    $imageurls = array(
      'http://j.mycom.jp/articles/2011/03/07/appinventor/images/006l.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_lockerz1() {
    $url = 'http://lockerz.com/s/71921454';
    $title = "Lockerz.com .:. Butter_nekojump's Photos -";
    $imageurls = array(
      'http://c0013619.cdn1.cloudfiles.rackspacecloud.com/x2_4496f2e',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_dengeki1() {
    $url = 'http://news.dengeki.com/elem/000/000/364/364901/img.html';
    $title = '【App通信】iPad 2が満を持して発売！ 美少女姉妹による萌え系紙芝居アプリも - 電撃オンライン';
    $imageurls = array(
      'http://news.dengeki.com/elem/000/000/364/364901/c20110502_app_18_cs1w1_347x720.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_twitpic1() {
    $url = 'http://twitpic.com/1yggai';
    $response = ImageLnk::getImageInfo($url);

    $title = '良くお休みのようで';
    $actual = $response->getTitle();
    $this->assertSame($title, $actual);

    $referer = 'http://twitpic.com/1yggai/full';
    $actual = $response->getReferer();
    $this->assertSame($referer, $actual);

    foreach ($response->getImageURLs() as $imageurl) {
      $expect = 1;
      $actual = preg_match('/^http:\/\/s3\.amazonaws\.com\/twitpic\/photos\/full\/118340730\.jpg/', $imageurl);
      $this->assertSame($expect, $actual);
    }
  }

  // ======================================================================
  function test_itmedia1() {
    $url = 'http://image.itmedia.co.jp/l/im/news/articles/1102/08/l_ah_echo4.jpg';
    $title = '京セラ、デュアルスクリーンのAndroidスマートフォン「Echo」発表';
    $imageurls = array(
      'http://image.itmedia.co.jp/news/articles/1102/08/l_ah_echo4.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_nicovideo1() {
    $url = 'http://www.nicovideo.jp/watch/sm12589060';
    $title = '中野テルヲ　うっかり楽曲担当してしまったのであろうCM ‐ ニコニコ動画(原宿)';
    $imageurls = array(
      'http://tn-skr1.smilevideo.jp/smile?i=12589060',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_pixiv1() {
    $url = 'http://www.pixiv.net/member_illust.php?mode=medium&illust_id=10461576';
    $title = '凛として鼻血';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/10461576_m.png',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_pixiv2() {
    $url = 'http://www.pixiv.net/member_illust.php?mode=big&illust_id=10461576';
    $title = '「凛として鼻血」/「柴系」のイラスト [pixiv]';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/10461576.png',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_pixiv3() {
    $url = 'http://www.pixiv.net/member_illust.php?mode=manga&illust_id=18741440';
    $title = 'ははのひとってもマミさん【まどか☆マギカ】';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/18741440_p0.png',
      'http://img11.pixiv.net/img/taishi22/18741440_p1.png',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_pixiv4() {
    $url = 'http://www.pixiv.net/member_illust.php?mode=manga_big&illust_id=18741440&page=1';
    $title = '「ははのひとってもマミさん【まどか☆マギカ】」/「柴系」の漫画 [pixiv]';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/18741440_big_p1.png',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_yaplog1() {
    $url = 'http://yaplog.jp/atsukana/image/236/306';
    $title = '自分大好き日記(笑)の画像(2/5) :: 菜っ葉の『菜』！！';
    $imageurls = array(
      'http://img.yaplog.jp/img/07/pc/a/t/s/atsukana/0/306_large.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_youtube1() {
    $url = 'http://www.youtube.com/watch?v=Tlmho7SY-ic&feature=player_embedded';
    $title = 'YouTube Turns Five!';
    $imageurls = array(
      'http://i1.ytimg.com/vi/Tlmho7SY-ic/default.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_yfrog1() {
    $url = 'http://yfrog.com/1xj3nvj';
    $title = 'yfrog Photo : http://yfrog.com/1xj3nvj Shared by atty303';
    $imageurls = array(
      'http://img69.yfrog.com/img69/7185/j3nv.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_photozou1() {
    $url = 'http://photozou.jp/photo/show/744707/79931926';
    $response = ImageLnk::getImageInfo($url);

    $title = 'テスト裏にキュゥべぇ描いた... - 写真共有サイト「フォト蔵」';
    $actual = $response->getTitle();
    $this->assertSame($title, $actual);

    $referer = 'http://photozou.jp/photo/photo_only/744707/79931926';
    $actual = $response->getReferer();
    $this->assertSame($referer, $actual);

    foreach ($response->getImageURLs() as $imageurl) {
      $expect = 1;
      $actual = preg_match('/^http:\/\/art22\.photozou\.jp\/bin\/photo\/79931926\/org/', $imageurl);
      $this->assertSame($expect, $actual);
    }
  }

  function test_photozou2() {
    $url = 'http://photozou.jp/photo/photo_only/744707/79931926?size=450';
    $response = ImageLnk::getImageInfo($url);

    $title = 'テスト裏にキュゥべぇ描いた... - 写真共有サイト「フォト蔵」';
    $actual = $response->getTitle();
    $this->assertSame($title, $actual);

    $referer = 'http://photozou.jp/photo/photo_only/744707/79931926';
    $actual = $response->getReferer();
    $this->assertSame($referer, $actual);

    foreach ($response->getImageURLs() as $imageurl) {
      $expect = 1;
      $actual = preg_match('/^http:\/\/art22\.photozou\.jp\/bin\/photo\/79931926\/org/', $imageurl);
      $this->assertSame($expect, $actual);
    }
  }

  // ======================================================================
  function test_twipple1() {
    $url = 'http://p.twipple.jp/6FGRA';
    $title = 'オレもマジでつぶやき内容に注意しよう&h... : ついっぷるフォト';
    $imageurls = array(
      'http://p.twipple.jp/data/6/F/G/R/A.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_impress1() {
    $url = 'http://game.watch.impress.co.jp/img/gmw/docs/448/930/html/psn01.jpg.html';
    $title = '[拡大画像] SCEJ、PlayStation NetworkとQriocityのサービスを5月28日から再開。安全管理措置を導入し、ゲームコンテンツの無償提供も';
    $imageurls = array(
      'http://game.watch.impress.co.jp/img/gmw/docs/448/930/psn01.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_tumblr1() {
    $url = 'http://titlebot.tumblr.com/post/5544499061';
    $title = '<p>ネコと和解せよ</p>';
    $imageurls = array(
      'http://26.media.tumblr.com/tumblr_llal1ttZ7W1qfqa6no1_400.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_picasa1() {
    $url = 'https://picasaweb.google.com/100474803495183280561/CatMagic#5516583424278584290';
    $title = 'Picasa Web Albums - 触れる猫カフェCatmagic@新... - Cat Magic';
    $imageurls = array(
      'http://lh6.ggpht.com/-aLmbGb0QF3k/TI7XZXHu2-I/AAAAAAAAAFY/KVW0kHhTe44/002.jpg',
      );
    $referer = 'http://picasaweb.google.com/100474803495183280561/CatMagic#5516583424278584290';
    $this->check_response($url, $title, $imageurls, $referer);
  }

  function test_picasa2() {
    $url = 'http://picasaweb.google.com/100474803495183280561/CatMagic#5516583424278584290';
    $title = 'Picasa Web Albums - 触れる猫カフェCatmagic@新... - Cat Magic';
    $imageurls = array(
      'http://lh6.ggpht.com/-aLmbGb0QF3k/TI7XZXHu2-I/AAAAAAAAAFY/KVW0kHhTe44/002.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_wikipedia1() {
    $url = 'http://en.wikipedia.org/wiki/File:PANSDeinonychus.JPG';
    $title = 'English:  Deinonychus antirrhopus skeleton, Philadelphia Academy of Natural Sciences';
    $imageurls = array(
      'http://upload.wikimedia.org/wikipedia/en/e/e6/PANSDeinonychus.JPG',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_instagram1() {
    $url = 'http://instagr.am/p/E6VjC/';
    $title = 'yfryer\'s photo: “おはよう”';
    $imageurls = array(
      'http://images.instagram.com/media/2011/05/29/9c4d66f169a24764961977326e2fc1cf_7.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_niconico1() {
    $url = 'http://video.niconico.com/watch/http://www.nicovideo.jp/watch/sm11187442';
    $title = '【弾いてみた】　ウルトラの奇跡　【 ○（ 0|0）o 】';
    $imageurls = array(
      'http://tn-skr3.smilevideo.jp/smile?i=11187442',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_owly1() {
    $url = 'http://ow.ly/i/bG2H';
    $title = 'Ow.ly - image uploaded by @jossfat (Joss Fat): ロシア寿命飲酒量曲線.jpg';
    $imageurls = array(
      'http://static.ow.ly/photos/original/bG2H.jpg',
      );
    $referer = 'http://ow.ly/i/bG2H/original';
    $this->check_response($url, $title, $imageurls, $referer);
  }

  // ======================================================================
  function test_natalie1() {
    $url = 'http://natalie.mu/comic/gallery/show/news_id/50403/image_id/77977';
    $title = 'コミックナタリー - 全高75cmでゲソ！「イカ娘」超BIGぬいぐるみが登場';
    $imageurls = array(
      'http://natalie.mu/media/comic/1105/extra/news_large_ika_roke1.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_natalie2() {
    $url = 'http://natalie.mu/music/gallery/show/news_id/50476/image_id/78087';
    $title = 'ナタリー - 怒髪天、5都市を回る自身初のホールツアー決定';
    $imageurls = array(
      'http://natalie.mu/media/1106/0601/extra/news_large_dohatsuten_topB.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_engadget_jp1() {
    $url = 'http://japanese.engadget.com/photos/asus-eee-pad-memo-3d-memic-hands-on/4173481/';
    $title = 'Engadget Japanese: Asus Eee Pad MeMO 3D / MeMIC hands on';
    $imageurls = array(
      'http://www.blogcdn.com/japanese.engadget.com/media/2011/05/asuseeepadmemohandsoncomputex1103-1306749954.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_engadget_jp2() {
    $url = 'http://japanese.engadget.com/photos/memorex-gaming-peripherals-e3-2011/4179706/';
    $title = 'Engadget Japanese: Memorex gaming Peripherals (E3 2011)';
    $imageurls = array(
      'http://www.blogcdn.com/japanese.engadget.com/media/2011/06/3dsgameselector.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_engadget1() {
    $url = 'http://www.engadget.com/photos/ubeam-wireless-power-demonstration-hands-on-at-d9/#4179665';
    $title = 'uBeam wireless power demonstration hands-on at D9 - Engadget Galleries';
    $imageurls = array(
      'http://www.blogcdn.com/www.engadget.com/media/2011/06/ubeam-demo-hands-on-d92877_103x88.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_engadget2() {
    $url = 'http://www.engadget.com/photos/intels-computex-2011-keynote/#4176987';
    $title = "Intel's Computex 2011 keynote - Engadget Galleries";
    $imageurls = array(
      'http://www.blogcdn.com/www.engadget.com/media/2011/05/11a531416e6_103x88.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }
}
