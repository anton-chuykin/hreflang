<?php

namespace Kg\Hreflang\Model\Hreflang;

class Url
{
    /**
     * @var $object
     */
    protected $object;

    /**
     * @var $storeId
     */
    protected $storeId;

    /**
     * @param $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @param $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }
}
