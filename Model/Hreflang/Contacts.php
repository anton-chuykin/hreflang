<?php
namespace Kg\Hreflang\Model\Hreflang;

use Magento\UrlRewrite\Model\UrlRewrite;
use Magento\Store\Model\StoreManagerInterface;

class Contacts extends Url implements EquivalentUrlInterface
{
    const TYPE = 'CONTACTS';
    const CONTACTS_URL = 'contact';
    const CONTACTS_DEFAULT_STORE_URL = 'customer-service';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlRewrite
     */
    private $urlRewrite;

    /**
     * Contacts constructor.
     * @param StoreManagerInterface $storeManager
     * @param UrlRewrite $urlRewrite
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        UrlRewrite $urlRewrite
    ) {
        $this->storeManager = $storeManager;
        $this->urlRewrite = $urlRewrite;
    }

    /**
     * Get equivalent contacts URL for a given storeId
     *
     * @return string
     */
    public function getEquivalentUrl()
    {
        $contactUrl = $this->getContactUrlRewrite();

        if ($this->isDefaultStoreView()) {
            $contactUrl = self::CONTACTS_DEFAULT_STORE_URL;
        }

        return $this->storeManager->getStore($this->storeId)
            ->getUrl($contactUrl);
    }

    /**
     * Check if store id is default view
     *
     * @return bool
     */
    private function isDefaultStoreView()
    {
        return (int)$this->storeId === (int)$this->storeManager
                ->getDefaultStoreView()->getStoreId();
    }

    /**
     * Get Contact Url, find if rewrite
     *
     * @return string
     */
    private function getContactUrlRewrite()
    {
        $urlContactRewriteCollection = $this->urlRewrite
            ->getCollection()
            ->addFieldToFilter('store_id', $this->storeId)
            ->addFieldToFilter('request_path', self::CONTACTS_URL);

        return $urlContactRewriteCollection->getFirstItem()
            ->getData('target_path') ?? self::CONTACTS_URL;
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
