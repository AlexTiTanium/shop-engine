<?php

namespace lib\MyCurl;

use Exception;

if (!function_exists('curl_init')) {
    throw new Exception('MyCurl needs the CURL PHP extension.');
}

class MyCurl {

  /**
   * Returns a cURL handle on success, FALSE on errors.
   *
   * @var resource
   */
  private $ch;

  /**
   * @var string
   */
  private $sendData;

  /**
   * MyCurl::__construct()
   *
   * @param string $url
   */
  public function __construct($url = ""){

    $this->ch = curl_init($url);

    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);

  }

  /**
   * MyCurl::setURL()
   *
   * @param string $url
   * @return void
   */
  public function setURL($url){
    curl_setopt($this->ch, CURLOPT_URL, $url);
  }

  /**
   * MyCurl::setURL()
   *
   * @internal param string $url
   * @return void
   */
  public function disableSslVerify(){
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
  }

  /**
   * MyCurl::setTimeout()
   *
   * @param int $sec - seconds
   * @return void
   */
  public function setTimeout($sec){
    curl_setopt($this->ch, CURLOPT_TIMEOUT, $sec);
  }

  /**
   * MyCurl::setMaxRedirects()
   *
   * @param int $redirects
   * @return void
   */
  public function setMaxRedirects($redirects){
    curl_setopt($this->ch, CURLOPT_MAXREDIRS, $redirects);
  }

  /**
   * MyCurl::saveCookie()
   *
   * @param mixed $file
   * @return void
   */
  public function saveCookie($file){
    curl_setopt($this->ch, CURLOPT_COOKIEJAR, $file);
  }

  /**
   * MyCurl::sendCookie()
   *
   * @param mixed $file
   * @return void
   */
  public function sendCookie($file){
    curl_setopt($this->ch, CURLOPT_COOKIEFILE, $file);
  }

  /**
   * MyCurl::setReferer()
   *
   * @param $referer
   * @return void
   */
  public function setReferer($referer){
    curl_setopt($this->ch, CURLOPT_REFERER, $referer);
  }

  /**
   * MyCurl::setUA()
   * Set user agent string
   *
   * @param string $ua
   * @return void
   */
  public function setUA($ua){
    curl_setopt($this->ch, CURLOPT_USERAGENT, $ua);
  }

  /**
   * MyCurl::setBasicAuthentication()
   *
   * @param string $login
   * @param string $password
   * @return void
   */
  public function setBasicAuthentication($login, $password){
    curl_setopt($this->ch, CURLOPT_USERPWD, $login . ':' . $password);
  }

  /**
   * MyCurl::setProxy()
   *
   * @param string $proxy
   * @param int $port
   * @param string|bool $pass
   * @return void
   */
  public function setProxy($proxy, $port, $pass = false){
    curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
    curl_setopt($this->ch, CURLOPT_PROXYPORT, $port);

    if($pass) {
      curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $pass);
    }
  }

  /**
   * MyCurl::post()
   *
   * @param array $array
   * @return void
   */
  public function post($array){
    curl_setopt($this->ch, CURLOPT_POST, true);
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($array));
    $this->sendData = http_build_query($array);
  }

  /**
   * MyCurl::get()
   *
   * @param $url
   * @param array $array
   * @return void
   */
  public function get($url,$array){

    $fullUrl = $url.'?'.http_build_query($array);
    $this->setURL($fullUrl);
    
    $this->sendData = $fullUrl;
  }

  /**
   * MyCurl::exec()
   *
   * @return string|false
   */
  public function exec(){
    return curl_exec($this->ch);
  }

  /**
   * MyCurl::error()
   *
   * @return string|''
   */
  public function error(){
    return curl_error($this->ch);
  }

  /**
   * MyCurl::errno()
   *
   * @return int|0
   */
  public function errno(){
    return curl_errno($this->ch);
  }

  /**
   * MyCurl::close()
   *
   * @return void
   */
  public function close(){
    curl_close($this->ch);
  }

}