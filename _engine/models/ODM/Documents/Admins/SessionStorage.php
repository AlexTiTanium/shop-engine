<?php

namespace Documents\Admins;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Documents\Admins\SessionStorage
 *
 * @ODM\Document(
 *     collection="admins_sessions_storage",
 *     indexes={
 *         @ODM\Index(keys={"user_id"="-1"}, options={"unique"="1", "safe"="1"}),
 *         @ODM\Index(keys={"sid"="-1"}, options={"unique"="1", "safe"="1"}),
 *         @ODM\Index(keys={"sid"="-1"}, options={"order"="desc", "unique"="1"})
 *     }
 * )
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class SessionStorage extends \lib\Doctrine\DoctrineModel
{
    /**
     * @var MongoId $id
     *
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $sid
     *
     * @ODM\Field(name="sid", type="string")
     */
    protected $sid;

    /**
     * @var string $user_id
     *
     * @ODM\Field(name="user_id", type="string")
     */
    protected $user_id;

    /**
     * @var string $ip
     *
     * @ODM\Field(name="ip", type="string")
     */
    protected $ip;

    /**
     * @var string $now
     *
     * @ODM\Field(name="now", type="string")
     */
    protected $now;

    /**
     * @var boolean $remember_me
     *
     * @ODM\Field(name="remember_me", type="boolean")
     */
    protected $remember_me;

    /**
     * @var int $exp_session
     *
     * @ODM\Field(name="exp_session", type="int")
     */
    protected $exp_session;

    /**
     * @var int $exp_online
     *
     * @ODM\Field(name="exp_online", type="int")
     */
    protected $exp_online;

    /**
     * @var Documents\Admin
     *
     * @ODM\ReferenceOne(targetDocument="Documents\Admin")
     */
    protected $user;


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
     * Set sid
     *
     * @param string $sid
     * @return SessionStorage
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
        return $this;
    }

    /**
     * Get sid
     *
     * @return string $sid
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set user_id
     *
     * @param string $userId
     * @return SessionStorage
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Get user_id
     *
     * @return string $userId
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return SessionStorage
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set now
     *
     * @param string $now
     * @return SessionStorage
     */
    public function setNow($now)
    {
        $this->now = $now;
        return $this;
    }

    /**
     * Get now
     *
     * @return string $now
     */
    public function getNow()
    {
        return $this->now;
    }

    /**
     * Set remember_me
     *
     * @param boolean $rememberMe
     * @return SessionStorage
     */
    public function setRememberMe($rememberMe)
    {
        $this->remember_me = $rememberMe;
        return $this;
    }

    /**
     * Get remember_me
     *
     * @return boolean $rememberMe
     */
    public function getRememberMe()
    {
        return $this->remember_me;
    }

    /**
     * Set exp_session
     *
     * @param int $expSession
     * @return SessionStorage
     */
    public function setExpSession($expSession)
    {
        $this->exp_session = $expSession;
        return $this;
    }

    /**
     * Get exp_session
     *
     * @return int $expSession
     */
    public function getExpSession()
    {
        return $this->exp_session;
    }

    /**
     * Set exp_online
     *
     * @param int $expOnline
     * @return SessionStorage
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
     * Set user
     *
     * @param Documents\Admin $user
     * @return SessionStorage
     */
    public function setUser(\Documents\Admin $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Documents\Admin $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
