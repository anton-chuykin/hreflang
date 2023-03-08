<?php
namespace Kg\Hreflang\Model;

class Config
{
    const XML_PATH_HREFLANG_ENABLED = 'hreflang/general/enabled';
    const XML_PATH_LOCALE_CODE = 'general/locale/code';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var $storeScope
     */
    protected $storeScope;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    }

    /**
     * Check if Hreflang is enabled
     *
     * @return bool
     */
    public function isHreflangEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_HREFLANG_ENABLED,
            $this->storeScope
        );
    }

    /**
     * Get locale code for store
     * @param int $storeId
     * @return bool
     */
    public function getLocaleCode($storeId)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LOCALE_CODE,
            $this->storeScope,
            $storeId
        );
    }
}
