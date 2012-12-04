<?php

namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Documents\Admin
 *
 * @ODM\Document(
 *     collection="admins",
 *     repositoryClass="models\ODM\Repositories\AdminRepository",
 *     indexes={
 *         @ODM\Index(keys={"login"="-1"}, options={"unique"="1", "safe"="1"})
 *     }
 * )
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class Admin extends \lib\Doctrine\DoctrineModel
{
    /**
     * @var MongoId $id
     *
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @var date $date
     *
     * @ODM\Field(name="date", type="date")
     */
    protected $date;

    /**
     * @var string $login
     *
     * @ODM\Field(name="login", type="string")
     */
    protected $login;

    /**
     * @var string $password
     *
     * @ODM\Field(name="password", type="string")
     */
    protected $password;

    /**
     * @var string $email
     *
     * @ODM\Field(name="email", type="string")
     */
    protected $email;

    /**
     * @var boolean $enable
     *
     * @ODM\Field(name="enable", type="boolean")
     */
    protected $enable;

    /**
     * @var string $forgot_password_hash
     *
     * @ODM\Field(name="forgot_password_hash", type="string")
     */
    protected $forgot_password_hash;

    /**
     * @var date $forgot_password_date
     *
     * @ODM\Field(name="forgot_password_date", type="date")
     */
    protected $forgot_password_date;

    /**
     * @var int $exp_online
     *
     * @ODM\Field(name="exp_online", type="int")
     */
    protected $exp_online;

    /**
     * @var string $activationHash
     *
     * @ODM\Field(name="activationHash", type="string")
     */
    protected $activationHash;

    /**
     * @var boolean $isActivated
     *
     * @ODM\Field(name="isActivated", type="boolean")
     */
    protected $isActivated;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return Admin
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Admin
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Get login
     *
     * @return string $login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Admin
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     * @return Admin
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean $enable
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set forgot_password_hash
     *
     * @param string $forgotPasswordHash
     * @return Admin
     */
    public function setForgotPasswordHash($forgotPasswordHash)
    {
        $this->forgot_password_hash = $forgotPasswordHash;
        return $this;
    }

    /**
     * Get forgot_password_hash
     *
     * @return string $forgotPasswordHash
     */
    public function getForgotPasswordHash()
    {
        return $this->forgot_password_hash;
    }

    /**
     * Set forgot_password_date
     *
     * @param date $forgotPasswordDate
     * @return Admin
     */
    public function setForgotPasswordDate($forgotPasswordDate)
    {
        $this->forgot_password_date = $forgotPasswordDate;
        return $this;
    }

    /**
     * Get forgot_password_date
     *
     * @return date $forgotPasswordDate
     */
    public function getForgotPasswordDate()
    {
        return $this->forgot_password_date;
    }

    /**
     * Set exp_online
     *
     * @param int $expOnline
     * @return Admin
     */
    public function setExpOnline($expOnline)
    {
        $this->exp_online = $expOnline;
        return $this;
    }

    /**
     * Get exp_online
     *
     * @return int $expOnline
     */
    public function getExpOnline()
    {
        return $this->exp_online;
    }

    /**
     * Set activationHash
     *
     * @param string $activationHash
     * @return Admin
     */
    public function setActivationHash($activationHash)
    {
        $this->activationHash = $activationHash;
        return $this;
    }

    /**
     * Get activationHash
     *
     * @return string $activationHash
     */
    public function getActivationHash()
    {
        return $this->activationHash;
    }

    /**
     * Set isActivated
     *
     * @param boolean $isActivated
     * @return Admin
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
        return $this;
    }

    /**
     * Get isActivated
     *
     * @return boolean $isActivated
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }
}
