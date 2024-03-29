<?php //-*- Mode: php; indent-tabs-mode: nil; -*-

class ImageLnkResponse {
  private $title_ = '';
  private $referer_ = '';
  private $imageurls_ = array();
  private $backlink_ = '';

  private static function normalize($string) {
    return @iconv("UTF-8", "UTF-8//IGNORE", $string);
  }
  private static function decode($string) {
    $string = self::normalize($string);
    $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    $string = self::normalize($string);
    return $string;
  }

  public function setTitle($newvalue) {
    $this->title_ = trim(self::decode($newvalue));
  }
  public function getTitle() {
    return $this->title_;
  }

  public function addImageURL($newvalue) {
    $this->imageurls_[] = self::decode($newvalue);
  }
  public function getImageURLs() {
    return $this->imageurls_;
  }

  public function setReferer($newvalue) {
    $this->referer_ = self::normalize($newvalue);
  }
  public function getReferer() {
    return $this->referer_;
  }

  public function setBackLink($newvalue) {
    $this->backlink_ = self::normalize($newvalue);
  }
  public function getBackLink() {
    return $this->backlink_;
  }
}
