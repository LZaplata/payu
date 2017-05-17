<?php
/**
 * Created by PhpStorm.
 * User: zap
 * Date: 15.5.17
 * Time: 14:20
 */

namespace LZaplata\PayU;


use Nette\Application\Responses\RedirectResponse;
use Nette\Object;

class Service extends Object
{
    /** @var  int */
    public $posId;

    /** @var  string */
    public $posAuthKey;

    /** @var  bool */
    public $sandbox;

    /** @var  string */
    public $key1;

    /** @var  string */
    public $key2;

    /**
     * Service constructor.
     *
     * @param int $posId
     * @param string $posAuthKey
     * @param bool $sandbox
     * @param string $key1
     * @param string $key2
     */
    public function __construct($posId, $posAuthKey, $sandbox, $key1, $key2)
    {
        $this->setSandbox($sandbox);
        $this->setPosId($posId);
        $this->setSecondKey($key2);
        $this->setFirstKey($key1);
        $this->setPosAuthKey($posAuthKey);
    }

    /**
     * Sets PayU POS ID
     *
     * @param int $id
     * @return self
     */
    public function setPosId($id)
    {
        $this->posId = $id;

        \OpenPayU_Configuration::setMerchantPosId($id);
        \OpenPayU_Configuration::setOauthClientId($id);

        return $this;
    }

    /**
     * Sets authorization POS key
     *
     * @param string $key
     * @return $this
     */
    public function setPosAuthKey($key)
    {
        $this->posAuthKey = $key;

        return $this;
    }

    /**
     * Sets environment
     *
     * @param bool $sandbox
     * @return self
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;

        \OpenPayU_Configuration::setEnvironment($sandbox ? "sandbox" : "secure");

        return $this;
    }

    /**
     * Sets first key (MD5)
     *
     * @param string $key
     * @return self
     */
    public function setFirstKey($key)
    {
        $this->key1 = $key;

        \OpenPayU_Configuration::setOauthClientSecret($key);

        return $this;
    }

    /**
     * Sets second key (MD5)
     *
     * @param string $key
     * @return self
     */
    public function setSecondKey($key)
    {
        $this->key2 = $key;

        \OpenPayU_Configuration::setSignatureKey($key);

        return $this;
    }

    /**
     * Create new order
     *
     * @param array $values
     * @return \OpenPayU_Result
     */
    public function createOrder($values)
    {
        $values["merchantPosId"] = $this->posId;
        $values["customerIp"] = "127.0.0.1";

        return \OpenPayU_Order::create($values);
    }

    /**
     * Process order result by redirecting to PayU gateway
     *
     * @param \OpenPayU_Result $order
     * @return RedirectResponse
     */
    public function pay(\OpenPayU_Result $order)
    {
        return new RedirectResponse($order->getResponse()->redirectUri);
    }
}