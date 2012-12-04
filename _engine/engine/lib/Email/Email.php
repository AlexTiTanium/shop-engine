<?php

namespace lib\Email;

use Swift_Mailer;
use lib\EngineExceptions\SystemException;
use Swift_Message;
use Swift_MailTransport;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Swift_Preferences;

use lib\Tpl\Template;
use lib\Core\Config;
use lib\Templates\TemplatesManager;

class Email {

  /**
   * @var Template
   */
  private $body;

  /**
   * @var array
   */
  private $sendTo = array();

  /**
   * @var Swift_Message
   */
  private $mail;

  /**
   * @var int
   */
  private $numSendSuccess = 0;

  /**
   * @var array
   */
  private $failedRecipients = array();

  /**
   * @var Swift_Mailer
   */
  static private $mailer;

  /**
   * Email::constructor
   */
  static public function construct(){

    $configEmail = Config::get('email');

    require_once PATH_VENDOR . 'Swift' . DS . 'swift_init.php';

    Swift_Preferences::getInstance()->setCharset($configEmail->emailCharset);
    Swift_Preferences::getInstance()->setTempDir(PATH_CACHE_SWIFT);
    Swift_Preferences::getInstance()->setCacheType('array');

    switch($configEmail->transport) {
      case 'smtp':
        $transport = Swift_SmtpTransport::newInstance($configEmail->smtpHost, $configEmail->smtpPort);
        break;
      case 'sendmail':
        $transport = Swift_SendmailTransport::newInstance($configEmail->sendMailPath);
        break;
      default:
        $transport = Swift_MailTransport::newInstance();
        break;
    }

    self::$mailer = Swift_Mailer::newInstance($transport);

  }

  /**
   *
   * @param \lib\Tpl\Template $body
   * @param bool|string $fromEmail
   * @return \lib\Email\Email
   */
  public function __construct(Template $body, $fromEmail = false){

    if(!$fromEmail) {
      $fromEmail = Config::get('email')->siteEmail;
    }

    $this->body = $body;

    $this->mail = Swift_Message::newInstance()->setFrom(array($fromEmail => Config::get('system')->siteUrl));
  }

  /**
   * @param string|array $keyOrArray
   * @param mixed $value
   */
  public function set($keyOrArray, $value = false){

    if(is_string($keyOrArray)){
      $this->body->set($keyOrArray, $value);
    }

    if(is_array($keyOrArray) and $value === false){
      $this->body->set($keyOrArray);
    }

  }

  /**
   * @param string $subject
   * @param string|array $sendToEmails
   * @param bool|string $userName
   * @internal param array|string $sendTo
   *
   * @example array(
   *   [0]=>array('perf1@progrmist.ru', 'Piter')
   *   [1]=>array('perf2@progrmist.ru', 'Vasiya')
   * }
   *
   * @example array(
   *   [0]=>'perf1@progrmist.ru'
   *   [1]=>'perf2@progrmist.ru'
   * }
   *
   * @example array(
   *   [perf1@progrmist.ru]=>'Piter'
   *   [perf2@progrmist.ru]=>'Vasiya'
   * }
   *
   * @return int - The return value is the number of recipients who were accepted for delivery.
   */
  public function send($subject, $sendToEmails, $userName = false){

    $mail = clone $this->mail;

    $this->setRecipient($sendToEmails, $userName);

    $mail->setSubject($subject);
    $mail->setBody($this->body->toString(), 'text/html');

    foreach($this->sendTo as $value){
      $mail->setTo($value);
      $this->numSendSuccess += self::$mailer->send($mail, $this->failedRecipients);
    }

    return $this->numSendSuccess;
  }

  /**
   * @param string|array $sendToEmails
   * @param bool|string $userName
   * @throws \lib\EngineExceptions\SystemException
   * @return void
   */
  private function setRecipient($sendToEmails, $userName = false){

    $this->sendTo = array();

    if(is_string($sendToEmails) and $userName === false){
      $name = explode('@',$sendToEmails);
      $this->sendTo[] = array($sendToEmails=>$name[0]);
      return;
    }

    if(is_string($sendToEmails) and $userName !== false){
      $this->sendTo[] = array($sendToEmails=>$userName);
      return;
    }

    if(is_array($sendToEmails) and empty($sendToEmails)){
      throw new SystemException('sendToEmails is empty');
    }

    foreach($sendToEmails as $key=>$value) {

      if(is_array($value)){
        $this->sendTo[] = array($value[0]=>$value[1]);
      }

      if(is_string($value)){

        if(is_string($key)){
          $this->sendTo[] = array($key=>$value);
          continue;
        }

        $name = explode('@',$value);
        $this->sendTo[] = array($value=>$name[0]);
      }

    }

  }

}

Email::construct();