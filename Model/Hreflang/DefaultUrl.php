<?php
namespace Kg\Hreflang\Model\Hreflang;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;

class DefaultUrl extends Url implements EquivalentUrlInterface
{
    const TYPE = 'DEFAULT';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * DefaultUrl constructor.
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlInterface
     * @param ScopeConfigInterface $scopeInterface
     * @param RequestInterface $request
     */

    public function __construct(
        StoreManagerInterface $storeManager,
        UrlInterface $urlInterface,
        ScopeConfigInterface $scopeInterface,
        RequestInterface $request
    ) {
        $this->storeManager = $storeManager;
        $this->urlInterface = $urlInterface;
        $this->_scopeConfig = $scopeInterface;
        $this->request = $request;
    }

    /**
     * Get equivalent URL for store Id
     *
     * @return string
     */
    public function getEquivalentUrl()
    {
        $urlParsed = parse_url($this->urlInterface->getCurrentUrl());
        $layout = $this->request->getFullActionName();
        if ($layout == 'ambrand_index_index') {
            $wineryUrl = $this->_scopeConfig->getValue(
                'amshopby_brand/general/url_key',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->storeId
            );
            $lastElemArray = explode('/', end($urlParsed));
            $lastElem = end($lastElemArray);
            return $this->storeManager->getStore($this->storeId)
                ->getBaseUrl(). $wineryUrl . '/' . $lastElem;

        }
        return $this->storeManager->getStore($this->storeId)
            ->getUrl(ltrim($urlParsed['path'], '/'));
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
