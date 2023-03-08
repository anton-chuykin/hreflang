<?php
namespace Kg\Hreflang\Model\Hreflang;

interface EquivalentUrlInterface
{
    /**
     * @param $object
     * @return mixed
     */
    public function setObject($object);

    /**
     * @param $storeId
     * @return mixed
     */
    public function setStoreId($storeId);

    /**
     * @return mixed
     */
    public function getEquivalentUrl();

    /**
     * @return string
     */
    public function getType();
}
