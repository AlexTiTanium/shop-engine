<?php

namespace lib\Captcha;

use lib\Session\Session;

class Captcha {

  private $image;
  private $font;
  private $group;
  private $numbers;

  private $imageHeight;
  private $imageWidth;
  private $imageFill;
  private $numbersAmount;
  private $fontSize;
  private $additionalImageSize;
  private $textNum;
  private $colors;
  private $angle;
  private $fontsArray;

  private $pathToFonts;


  public function __construct(){

    $this->pathToFonts = dirname(__FILE__) . DS . 'fonts' . DS;

    $this->imageHeight = '60'; // Высота
    $this->imageWidth = '270'; // Ширена
    $this->imageFill = array('red' => '255', 'green' => '255', 'blue' => '255'); // Цвет фона
    $this->numbersAmount = array('min' => '3', 'max' => '3'); // Кол-во цифр
    $this->fontSize = array('min' => '15', 'max' => '22'); // Размер шрифта
    $this->additionalImageSize = 30; // Дополнительный размер всей картинки для размешения текста
    $this->textEnter = 'Введите только'; // Текст
    $this->textNum = 'цифры'; // текст
    $this->colors = array( // масивчик с цифрами
      'red' => array('name' => 'красные', 'nums' => '', 'index' => array('red' => '255', 'green' => '0', 'blue' => '0')),
      'bleak' => array('name' => 'черные', 'nums' => '', 'index' => array('red' => '0', 'green' => '0', 'blue' => '0')),
      'green' => array('name' => 'зеленые', 'nums' => '', 'index' => array('red' => '11', 'green' => '159', 'blue' => '0')),
      'blue' => array('name' => 'синие', 'nums' => '', 'index' => array('red' => '0', 'green' => '0', 'blue' => '255')));
    //Максимальный угол отклонения от горизонтали по часовой стрелке и против, по умолчанию-20
    $this->angle = array('max' => '2', 'min' => '50');

    $this->fontsArray = glob($this->pathToFonts . "*.ttf");
    $this->createImage();
  }

  /**
   * Captcha::show();
   *
   * Добавить шрифт
   *
   * @return void
   */
  public function show(){
    //header("Content-type: image/png");
    imagepng($this->image);
    imagedestroy($this->image);
  }

  /**
   * Captcha::getCaptcha();
   *
   * Получить готовое изображение
   *
   * @return
   */
  public function getCaptcha(){
    return $this->image;
  }

  /**
   * Captcha::createImage();
   *
   * Создаем изображение
   *
   * @return void
   */
  private function createImage(){

    $this->getImage();
    $numbers = $this->getRand('numbersAmount');
    $this->group = 1;
    $this->numbers = $numbers;

    foreach($this->colors as $k => $v) {
      $this->group++;
      for($n = 0; $numbers > $n; $n++) {
        $this->addNum($n, $k);
      }
    }

    $color = $this->randColor();
    $this->addText($this->textEnter . ' ' . $this->colors[$color]['name'] . ' ' . $this->textNum);

    Session::setFlash('captcha_code', md5($this->colors[$color]['nums'] . SYSTEM_CODE));
  }

  /**
   * Captcha::win2uni();
   *
   * Перекодировка, если нужна
   *
   * @param string $str
   * @return string
   */
  private function win2uni($str){
    $str = convert_cyr_string($str, 'w', 'i'); // преобразование win1251 -> iso8859-5
    // преобразование iso8859-5 -> unicode:
    for($result = '', $i = 0; $i < mb_strlen($str); $i++) {
      $charcode = ord($str[$i]);
      $result .= ($charcode > 175) ? "&#" . (1040 + ($charcode - 176)) . ";" : $str[$i];
    }

    return $result;
  }

  /**
   * Captcha::getRand();
   *
   * Получаем случайные числа в определенных рамках
   *
   * @param $va_r
   * @internal param string $str - имя свойства в котором лежит массив с min, max ключами
   * @return int
   */
  private function getRand($va_r){
    return rand($this->{$va_r}['min'], $this->{$va_r}['max']);
  }

  /**
   * Captcha::getCoordinatesOfChar();
   *
   * Просчет кординат чтоб циферки не толкались
   *
   * @param string $char
   * @return array
   */
  private function getCoordinatesOfChar($char){
    $y = ((($this->imageHeight - $this->font) + rand(1, 8) * 20) / 4 + $this->font) - 10; //-30
    $x = rand(($this->imageWidth / $this->numbers - $this->font) / 2, $this->imageWidth / $this->numbers - $this->font) + ($char * $this->imageWidth / $this->numbers) - 10;

    return array('x' => $x, 'y' => $y);
  }

  /**
   * Captcha::getAngle();
   *
   * Получаем угол наклона
   *
   * @return int
   */
  private function getAngle(){
    return rand(360 - $this->getRand('angle'), 360 + $this->getRand('angle'));
  }

  /**
   * Captcha::getFont();
   *
   * Получаем шрифт случайный
   *
   * @return string - path
   */
  private function getFont(){
    $fontIndex = rand(0, count($this->fontsArray) - 1);
    return $this->fontsArray[$fontIndex];
  }

  /**
   * Captcha::randColor();
   *
   * Получаем случайный цвет
   *
   * @return string - color name
   */
  private function randColor(){

    $rand = rand(0, count($this->colors) - 1);
    $colors = array_keys($this->colors);

    return $colors[$rand];
  }

  /**
   * Captcha::getImage();
   *
   * Создаем пустую картинку
   *
   * @return string - path
   */
  private function getImage(){
    $this->image = imagecreatetruecolor($this->imageWidth, $this->imageHeight + $this->additionalImageSize);
    $fill = imagecolorallocate($this->image, $this->imageFill['red'], $this->imageFill['green'], $this->imageFill['blue']);
    imagefill($this->image, 0, 0, $fill);
  }

  /**
   * Captcha::getChar();
   *
   * Просто число для картинки сами циферки
   *
   * @return string - char
   */
  private function getChar(){
    return rand(0, 9);
  }

  /**
   * Captcha::getChar();
   *
   * Просто число для картинки сами циферки
   *
   * @param $char
   * @param $color
   * @return void
   */
  private function addNum($char, $color){

    $this->font = $this->getRand('fontSize');
    $coordinatesOfChar = $this->getCoordinatesOfChar($char);
    $char = $this->getChar();

    imagettftext($this->image, $this->font, $this->getAngle(), $coordinatesOfChar['x'], $coordinatesOfChar['y'], $this->color($color), $this->getFont(), $char);

    $this->colors[$color]['nums'] .= $char;
  }

  /**
   * Captcha::color();
   *
   * Цвет в палитру
   *
   * @param $color
   * @return int color
   */
  private function color($color){
    return imagecolorallocate($this->image, $this->colors[$color]['index']['red'], $this->colors[$color]['index']['green'], $this->colors[$color]['index']['blue']);
  }

  /**
   * Captcha::addText();
   *
   * Добавить шрифт
   *
   * @param $str
   * @return int color
   */
  private function addText($str){
    //$str=$this->win2uni($str);
    imagettftext($this->image, 10, 0, 2, $this->imageHeight + $this->additionalImageSize - 4, $this->color('bleak'), $this->fontsArray[3], $str);
  }

}