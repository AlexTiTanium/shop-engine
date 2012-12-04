<?php
use lib\Yaml\Yaml;
use lib\Core\Data;
use lib\Core\Data\DataValidator;

/**
 * Created by JetBrains PhpStorm.
 * User: Alexander
 * Date: 02.10.12
 * Time: 17:40
 * To change this template use File | Settings | File Templates.
 */

class DataValidatorData2 extends PHPUnit_Framework_TestCase {

  /**
   * @var DataValidator
   */
  protected $dataValidator;

  /**
   * @var Data
   */
  protected $dataFixture;
  protected $scheme;
  protected $arrayData;

  protected function setUp() {

    $this->scheme = Yaml::load(file_get_contents('core/data/assert/data2.yml'));
    $this->assertNotEmpty($this->scheme);
  }

  protected function tearDown() {

    $this->arrayData = null;
    $this->dataValidator = null;
    $this->dataFixture = null;
    $this->scheme = null;
  }

  public function testStringReq(){

    $this->dataFixture = new Data(array('stringKey'=>'abc'));
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringKeyReq']), false);
    $this->assertEquals('abc', $this->dataFixture->get('stringKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testStringReqSmall(){

    $this->dataFixture = new Data(array('stringKey'=>'ab')); // min 3
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringKeyReq']), false);
  }

  public function testStringReqNotBig(){

    $this->dataFixture = new Data(array('stringKey'=>'567891011121314')); // max 15
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringKeyReq']), false);
    $this->assertEquals('567891011121314', $this->dataFixture->get('stringKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testStringReqBig(){

    $this->dataFixture = new Data(array('stringKey'=>'5678910111213141')); // max 15
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringKeyReq']), false);
  }

  public function testStringNotReq(){

    $this->dataFixture = new Data(array('stringKey'=>'abc'));
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringNotReq']), false);
    $this->assertEquals('abc', $this->dataFixture->get('stringKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testStringNotReqMin(){

    $this->dataFixture = new Data(array('stringKey'=>'ab')); // min 3
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringNotReq']), false);
    $this->assertEquals('ab', $this->dataFixture->get('stringKey'));
  }

  public function testStringNotReqEmpty(){

    $this->dataFixture = new Data(array('stringKey'=>'')); // min 3
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringNotReq']), false);
    $this->assertEquals('', $this->dataFixture->get('stringKey'));
  }

  public function testStringNotReqNoVar(){

    $this->dataFixture = new Data(array()); // min 3
    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringNotReq']), false);
    $this->assertEquals('', $this->dataFixture->get('stringKey',''));
  }

  public function testIntReq(){

    $this->dataFixture = new Data(array('intKey'=>10)); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
    $this->assertEquals(10, $this->dataFixture->get('intKey'));
  }

  public function testIntReqMin(){

    $this->dataFixture = new Data(array('intKey'=>3)); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
    $this->assertEquals(3, $this->dataFixture->get('intKey'));
  }

  public function testIntReqMax(){

    $this->dataFixture = new Data(array('intKey'=>15)); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
    $this->assertEquals(15, $this->dataFixture->get('intKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntReqMin3(){

    $this->dataFixture = new Data(array('intKey'=>2)); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntReqMax15(){

    $this->dataFixture = new Data(array('intKey'=>16)); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntReqEmpty(){

    $this->dataFixture = new Data(array('intKey'=>'')); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntReqNoVar(){

    $this->dataFixture = new Data(array()); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerReq']), false);
  }

  public function testIntNotReq(){

    $this->dataFixture = new Data(array()); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerNoReq']), false);
    $this->assertNull($this->dataFixture->get('intKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntNotReqEmpty(){

    $this->dataFixture = new Data(array('intKey'=>'')); // min 3 max 15
    $this->dataFixture->validate(array('intKey' => $this->scheme['integerNoReq']), false);
  }

  public function testFloatReq(){

    $this->dataFixture = new Data(array('floatKey'=>5.55)); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
    $this->assertEquals(5.55, $this->dataFixture->get('floatKey'));
  }

  public function testFloatReqMin(){

    $this->dataFixture = new Data(array('floatKey'=>3)); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
    $this->assertEquals(3, $this->dataFixture->get('floatKey'));
  }

  public function testFloatReqMax(){

    $this->dataFixture = new Data(array('floatKey'=>15)); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
    $this->assertEquals(15, $this->dataFixture->get('floatKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testFloatReqMin2(){

    $this->dataFixture = new Data(array('floatKey'=>2)); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testFloatReqMax16(){

    $this->dataFixture = new Data(array('floatKey'=>16)); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testFloatReqEmpty(){

    $this->dataFixture = new Data(array('floatKey'=>'')); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testFloatReqNoVar(){

    $this->dataFixture = new Data(array()); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatReq']), false);
  }

  public function testFloatNotReq(){

    $this->dataFixture = new Data(array()); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatNoReq']), false);
    $this->assertNull($this->dataFixture->get('floatKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testFloatNotReqEmpty(){

    $this->dataFixture = new Data(array('floatKey'=>'')); // min 3 max 15
    $this->dataFixture->validate(array('floatKey' => $this->scheme['floatNoReq']), false);
  }

  public function testBooleanReq(){

    $this->dataFixture = new Data(array('booleanKey'=>true));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanReq']), false);
    $this->assertTrue($this->dataFixture->get('booleanKey'));
  }

  public function testBooleanReqFalse(){

    $this->dataFixture = new Data(array('booleanKey'=>false));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanReq']), false);
    $this->assertFalse($this->dataFixture->get('booleanKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testBooleanReqNull(){

    $this->dataFixture = new Data(array('booleanKey'=>null));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanReq']), false);
  }

  public function testBooleanReqEmpty(){

    $this->dataFixture = new Data(array('booleanKey'=>''));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanReq']), false);
    $this->assertFalse($this->dataFixture->get('booleanKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testBooleanReqNotVar(){

    $this->dataFixture = new Data(array());
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanReq']), false);
  }

  public function testBooleanNoReqNotVar(){

    $this->dataFixture = new Data(array());
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanNotReq']), false);
    $this->assertNull($this->dataFixture->get('booleanKey'));
  }

  public function testBooleanNoReqFalse(){

    $this->dataFixture = new Data(array('booleanKey'=>false));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanNotReq']), false);
    $this->assertFalse($this->dataFixture->get('booleanKey'));
  }

  public function testBooleanNoReqEmpty(){

    $this->dataFixture = new Data(array('booleanKey'=>''));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanNotReq']), false);
    $this->assertFalse($this->dataFixture->get('booleanKey'));
  }

  public function testBooleanNoReqTrue(){

    $this->dataFixture = new Data(array('booleanKey'=>true));
    $this->dataFixture->validate(array('booleanKey' => $this->scheme['booleanNotReq']), false);
    $this->assertTrue($this->dataFixture->get('booleanKey'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testBadEmail(){

    $this->dataFixture = new Data(array('email'=>'bad-e-mail@.com'));
    $this->dataFixture->validate(array('email' => $this->scheme['email']), false);
  }

  public function testGoodEmail(){

    $email = 'good-e-mail@mail.com';
    $this->dataFixture = new Data(array('email'=>$email));
    $this->dataFixture->validate(array('email' => $this->scheme['email']), false);
    $this->assertEquals($email, $this->dataFixture->get('email'));
  }
}