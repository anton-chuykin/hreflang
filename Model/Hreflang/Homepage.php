<?php
namespace Kg\Hreflang\Model\Hreflang;

use Magento\Store\Model\StoreManagerInterface;

class Homepage extends Url implements EquivalentUrlInterface
{
    const TYPE = 'HOMEPAGE';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Homepage constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Get equivalent homepage URL for a given storeId
     *
     * @return string
     */
    public function getEquivalentUrl()
    {
        return $this->storeManager->getStore($this->storeId)->getBaseUrl();
    }

    /**
     * Get type of the hreflang
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }
}
