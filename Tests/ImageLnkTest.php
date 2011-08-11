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

  function test_ameblo3() {
    $url = 'http://ameblo.jp/strawberry-ayana/image-10963873926-11370958832.html';
    $response = ImageLnk::getImageInfo($url);

    $title = 'わんふぇす2の画像 | 竹達彩奈オフィシャルブログ「Strawberry Candy」Powe…';
    $actual = $response->getTitle();
    $this->assertSame($title, $actual);

    $referer = $url;
    $actual = $response->getReferer();
    $this->assertSame($referer, $actual);

    foreach ($response->getImageURLs() as $imageurl) {
      $expect = 1;
      $actual = preg_match('/http:\/\/stat.*\.ameba\.jp\/user_images\/20110724\/19\/strawberry-ayana\/ac\/1e\/j\/o0480064011370958832\.jpg/', $imageurl);
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
    $title = "Lockerz.com : Butter_nekojump's Photo";
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

  function test_itmedia2() {
    $url = 'http://image.itmedia.co.jp/l/im/nl/articles/1106/02/l_ky_robo_0602_5.jpg';
    $title = 'セグウェイが歩道を走る　つくばでロボットの公道走行実験スタート';
    $imageurls = array(
      'http://image.itmedia.co.jp/nl/articles/1106/02/l_ky_robo_0602_5.jpg',
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
    $title = '「凛として鼻血」/「柴系」のイラスト [pixiv]';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/10461576.png',
      );
    $referer = 'http://www.pixiv.net/member_illust.php?mode=big&illust_id=10461576';
    $this->check_response($url, $title, $imageurls, $referer);
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

  function test_pixiv5() {
    $url = 'http://www.pixiv.net/member_illust.php?mode=medium&illust_id=18741440';
    $title = '「ははのひとってもマミさん【まどか☆マギカ】」/「柴系」の漫画 [pixiv]';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/18741440_big_p0.png',
      );
    $referer = 'http://www.pixiv.net/member_illust.php?mode=manga_big&illust_id=18741440&page=0';
    $this->check_response($url, $title, $imageurls, $referer);
  }

  function test_pixiv6() {
    $url = 'http://touch.pixiv.net/member_illust.php?mode=medium&illust_id=18741440';
    $title = '「ははのひとってもマミさん【まどか☆マギカ】」/「柴系」の漫画 [pixiv]';
    $imageurls = array(
      'http://img11.pixiv.net/img/taishi22/18741440_big_p0.png',
      );
    $referer = 'http://www.pixiv.net/member_illust.php?mode=manga_big&illust_id=18741440&page=0';
    $this->check_response($url, $title, $imageurls, $referer);
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
      'http://a.yfrog.com/img69/7185/j3nv.jpg',
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

  function test_impress2() {
    $url = 'http://dc.watch.impress.co.jp/img/dcw/docs/422/882/html/009.jpg.html';
    $title = '写真で見るカシオ「TRYX」（β機） - デジカメWatch';
    $imageurls = array(
      'http://dc.watch.impress.co.jp/img/dcw/docs/422/882/009.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_impress3() {
    $url = 'http://k-tai.impress.co.jp/img/ktw/docs/460/236/html/sanzo.jpg.html';
    $title = 'ケータイ-[拡大画像]サンヨーホームズ、Android採用のロボットを搭載した住宅';
    $imageurls = array(
      'http://k-tai.impress.co.jp/img/ktw/docs/460/236/sanzo.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_impress4() {
    $url = 'http://akiba-pc.watch.impress.co.jp/hotline/20110806/image/kfrety4.html';
    $title = '[拡大画像]お買い得価格情報 - AKIBA PC Hotline!';
    $imageurls = array(
      'http://akiba-pc.watch.impress.co.jp/hotline/20110806/image/kfrety4.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_tumblr1() {
    $url = 'http://titlebot.tumblr.com/post/5544499061';
    $title = 'titlebot: ネコと和解せよ';
    $imageurls = array(
      'http://26.media.tumblr.com/tumblr_llal1ttZ7W1qfqa6no1_400.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_tumblr2() {
    $url = 'http://maegamipattun.tumblr.com/post/7815975799';
    $title = '前髪ぱっつん専用タンブラー';
    $imageurls = array(
      'http://www.tumblr.com/photo/1280/7815975799/1/tumblr_l1n113Lyub1qaxrtk',
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
    $title = 'PANSDeinonychus.JPG English: Deinonychus antirrhopus skeleton, Philadelphia Academy of Natural Sciences';
    $imageurls = array(
      'http://upload.wikimedia.org/wikipedia/commons/e/e6/PANSDeinonychus.JPG',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_instagram1() {
    $url = 'http://instagr.am/p/E6VjC/';
    $title = 'yfryer\'s photo: “おはよう”';
    $imageurls = array(
      'http://distillery.s3.amazonaws.com/media/2011/05/29/9c4d66f169a24764961977326e2fc1cf_7.jpg',
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
      'http://www.blogcdn.com/www.engadget.com/media/2011/06/ubeam-demo-hands-on-d92877.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_engadget2() {
    $url = 'http://www.engadget.com/photos/intels-computex-2011-keynote/#4176987';
    $title = "Intel's Computex 2011 keynote - Engadget Galleries";
    $imageurls = array(
      'http://www.blogcdn.com/www.engadget.com/media/2011/05/11a531416e6.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_4gamer1() {
    $url = 'http://www.4gamer.net/games/044/G004471/20110616072/screenshot.html?num=003';
    $title = '4Gamer.net ― スクリーンショット（「Wizardry Online」のCBT「機能テスト」先行体験プレイレポートを掲載。実態はやはり高難度……しかし序盤は「ロスト」の心配無用？）';
    $imageurls = array(
      'http://www.4gamer.net/games/044/G004471/20110616072/SS/003.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_4gamer2() {
    $url = 'http://www.4gamer.net/games/044/G004471/20110616072/screenshot.html';
    $title = '4Gamer.net ― スクリーンショット（「Wizardry Online」のCBT「機能テスト」先行体験プレイレポートを掲載。実態はやはり高難度……しかし序盤は「ロスト」の心配無用？）';
    $imageurls = array(
      'http://www.4gamer.net/games/044/G004471/20110616072/SS/001.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_gamespot_jp1() {
    $url = 'http://japan.gamespot.com/image/l/storage/35004222/storage/2011/06/17/77bbc7f0675cc5bac8edf6b5e30567c6/jjapp03.jpg';
    $title = 'iPhone/iPod touchアプリ「ジョジョの奇妙な花闘」本日6月17日より無料配信！関連画像';
    $imageurls = array(
      'http://japan.gamespot.com/storage/2011/06/17/93e0731c4580bb9a012cf2c9e3b818c1/watermark_jjapp03.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_famitsu1() {
    $url = 'http://www.famitsu.com/news/201106/images/00045516/qSmn53J8Boevo2zZqF3IYq6hCI37GJ7w.html';
    $title = '『侵略！イカ娘』が釣りゲームになって登場！　エビでイカを釣らなイカ？関連スクリーンショット・写真画像';
    $imageurls = array(
      'http://www.famitsu.com/news/201106/images/00045516/qSmn53J8Boevo2zZqF3IYq6hCI37GJ7w.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_famitsu2() {
    $url = 'http://www.famitsu.com/news/201106/images/00045487/AGE4AGCB21y4EX6lmIuMOTSYW3Bs4o9Q.html';
    $title = '『ギアーズ オブ ウォー 3』の真髄に迫る、“Horde”いよいよ解禁！【スタジオツアー1】関連スクリーンショット・写真画像';
    $imageurls = array(
      'http://www.famitsu.com/news/201106/images/00045487/AGE4AGCB21y4EX6lmIuMOTSYW3Bs4o9Q.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_zakzak1() {
    $url = 'http://www.zakzak.co.jp/zakspa/photos/20110624/zsp1106241001002-p1.htm';
    $title = '元ＡＶ女優「ミニスカ信仰」に異を唱えるショーパンのエロス  - ZAK×SPA！ - ZAKZAK';
    $imageurls = array(
      'http://www.zakzak.co.jp/zakspa/images/20110624/zsp1106241001002-p1.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_zakzak2() {
    $url = 'http://www.zakzak.co.jp/gravure/idol/photos/20110627/idl1106271244001-p1.htm';
    $title = '１６歳の森野朝美、スレンダーボディー炸裂にドキッ  - グラビアアイドル - ZAKZAK';
    $imageurls = array(
      'http://www.zakzak.co.jp/gravure/idol/images/20110627/idl1106271244001-p1.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_zakzak3() {
    $url = 'http://www.zakzak.co.jp/zakspa/photos/20110627/zsp1106271227001-p1.htm';
    $title = '【物欲刺激度】これなら３Ｄを楽しむ気になる！？  - ZAK×SPA！ - ZAKZAK';
    $imageurls = array(
      'http://www.zakzak.co.jp/zakspa/images/20110627/zsp1106271227001-p1.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_zakzak4() {
    $url = 'http://www.zakzak.co.jp/campaigngirl/j-beauty/photos/20110518/jbt1105181611000-p1.htm';
    $title = '株主様になってください！「オトメ☆コーポレーション」ゆうこ  - キャンギャル・イベント - ZAKZAK';
    $imageurls = array(
      'http://www.zakzak.co.jp/campaigngirl/j-beauty/images/20110518/jbt1105181611000-p1.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_zakzak5() {
    $url = 'http://www.zakzak.co.jp/gravure/idol/photos/20110806/idl1108061505000-p12.htm';
    $title = '巨乳れい、運動会でおっぱいモミモミ！  - グラビアアイドル - ZAKZAK';
    $imageurls = array(
      'http://www.zakzak.co.jp/gravure/idol/images/20110806/idl1108061505000-p12.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  // ======================================================================
  function test_uncyclopedia_ja1() {
    $url = 'http://ja.uncyclopedia.info/wiki/%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB:CaesiumSan_01.jpg';
    $title = 'ファイル:CaesiumSan 01.jpg - アンサイクロペディア';
    $imageurls = array(
      'http://images.uncyc.org/ja/2/25/CaesiumSan_01.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }

  function test_uncyclopedia_ja2() {
    $url = 'http://ja.uncyclopedia.info/wiki/%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB:Sigeru.jpg';
    $title = 'ファイル:Sigeru.jpg - アンサイクロペディア';
    $imageurls = array(
      'http://images.uncyc.org/ja/1/11/Sigeru.jpg',
      );
    $this->check_response($url, $title, $imageurls);
  }
}
