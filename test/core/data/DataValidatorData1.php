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

class DataValidatorData1 extends PHPUnit_Framework_TestCase {

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
  protected $rightArrayData;

  protected function setUp() {

    $this->arrayData = array(
      'stringKey' => 'String text',
      'stringWithTags' => 'String text <b>some b tag</b> <script type="text/javascript">alert("alert");</script>',
      'stringWithN' => 'text text ' . "\n" . ' text text',
      'integer100' => 100,
      'integer100andAbc' => '100abc', // convert to 100
      'integerAbcAnd100' => 'abc100', // will down
      'integerBad' => 'abc',          // will down
      'float98' => 98.99885,
      'float98abc' => '98.99885abc',  // convert to 98.99885
      'floatAbc98' => 'dfe98.99885',  // will down
      'stringFloat' => '98.99885',    // convert to 98.99885
      'booleanTrue' => true,
      'booleanFalse' => false,
      'booleanBad' => 'abc',          // will convert to true
      'booleanNull' => null,
      'notInScheme' => 'abs'
    );

    $this->rightArrayData = array(
      'stringKey' => 'String text',
      'stringWithTags' => 'String text some b tag alert(&#34;alert&#34;);',
      'stringWithN' => 'text text ' . "\n" . ' text text',
      'integer100' => 100,
      'integer100andAbc'=>100,
      'float98'=> 98.99885,
      'float98abc'=>98.99885,
      'stringFloat' => 98.99885,
      'booleanTrue' => true,
      'booleanFalse' => false,
      'booleanBad' => true,
      'booleanNull' => null,
    );

    $this->dataFixture = new Data($this->arrayData);

    $this->scheme = Yaml::load(file_get_contents('core/data/assert/data1.yml'));
    $this->assertNotEmpty($this->scheme);
  }

  protected function tearDown() {

    $this->arrayData = null;
    $this->rightArrayData = null;
    $this->dataValidator = null;
    $this->dataFixture = null;
    $this->scheme = null;
  }

  public function testStringTest() {

    $this->dataFixture->validate(array('stringKey' => $this->scheme['stringKey']), false);
    $this->assertEquals($this->rightArrayData['stringKey'], $this->dataFixture->get('stringKey'));
  }

  public function testStringWithTags() {

    $this->dataFixture->validate(array('stringWithTags' => $this->scheme['stringWithTags']), false);
    $this->assertEquals($this->rightArrayData['stringWithTags'], $this->dataFixture->get('stringWithTags'));
  }

  public function testStringWithN() {

    $this->dataFixture->validate(array('stringWithN' => $this->scheme['stringWithN']), false);
    $this->assertEquals($this->rightArrayData['stringWithN'], $this->dataFixture->get('stringWithN'));
  }

  public function testInteger100() {

    $this->dataFixture->validate(array('integer100' => $this->scheme['integer100']), false);
    $this->assertEquals($this->rightArrayData['integer100'], $this->dataFixture->get('integer100'));
  }

  public function testInteger100andAbc() {

    $this->dataFixture->validate(array('integer100andAbc' => $this->scheme['integer100andAbc']), false);
    $this->assertEquals($this->rightArrayData['integer100andAbc'], $this->dataFixture->get('integer100andAbc'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntegerAbcAnd100() {

    $this->dataFixture->validate(array('integerAbcAnd100' => $this->scheme['integerAbcAnd100']), false);
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testIntegerBad() {

    $this->dataFixture->validate(array('integerBad' => $this->scheme['integerBad']), false);
  }

  public function testFloat98() {

    $this->dataFixture->validate(array('float98' => $this->scheme['float98']), false);
    $this->assertEquals($this->rightArrayData['float98'], $this->dataFixture->get('float98'));
  }

  public function testFloat98abc() {

    $this->dataFixture->validate(array('float98abc' => $this->scheme['float98abc']), false);
    $this->assertEquals($this->rightArrayData['float98abc'], $this->dataFixture->get('float98abc'));
  }

  /**
   * @expectedException lib\Core\Data\DataValidationException
   */
  public function testFloatAbc98() {

    $this->dataFixture->validate(array('floatAbc98' => $this->scheme['floatAbc98']), false);
  }

  public function testStringFloat() {

    $this->dataFixture->validate(array('stringFloat' => $this->scheme['stringFloat']), false);
    $this->assertEquals($this->rightArrayData['stringFloat'], $this->dataFixture->get('stringFloat'));
  }

  public function testBooleanTrue() {

    $this->dataFixture->validate(array('booleanTrue' => $this->scheme['booleanTrue']), false);
    $this->assertEquals($this->rightArrayData['booleanTrue'], $this->dataFixture->get('booleanTrue'));
  }

  public function testBooleanFalse() {

    $this->dataFixture->validate(array('booleanFalse' => $this->scheme['booleanFalse']), false);
    $this->assertEquals($this->rightArrayData['booleanFalse'], $this->dataFixture->get('booleanFalse'));
  }

  public function testBooleanBad() {

    $this->dataFixture->validate(array('booleanBad' => $this->scheme['booleanBad']), false);
    $this->assertEquals($this->rightArrayData['booleanBad'], $this->dataFixture->get('booleanBad'));
  }

  public function testBooleanNull() {

    $this->dataFixture->validate(array('booleanNull' => $this->scheme['booleanNull']), false);
    $this->assertEquals($this->rightArrayData['booleanNull'], $this->dataFixture->get('booleanNull'));
  }

  public function testNotInSchemeAndNoData() {

    $this->assertNull($this->dataFixture->get('notInScheme1',null));
  }

}