<?php
namespace Kg\Hreflang\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Cms\Model\Page;
use Magento\Store\Model\StoreRepository;
use Magento\Store\Model\StoreManagerInterface;
use Kg\Hreflang\Model\Hreflang\Category;
use Kg\Hreflang\Model\HreflangBuilder;
use Kg\Hreflang\Model\Config;
use Kg\Hreflang\Model\LinkFactory;

class Hreflang extends \Magento\Framework\View\Element\Template
{
    /**
     * Product type const
     */
    const PRODUCT = 'PRODUCT';

    /**
     * Category type const
     */
    const CATEGORY = 'CATEGORY';

    /**
     * CMS type const
     */
    const CMS = 'CMS';

    /**
     * Homepage type const
     */
    const HOMEPAGE = 'HOMEPAGE';

    /**
     * @var $hreflang
     */
    protected $hreflang;

    /**
     * @var int $currentStoreId
     */
    protected $currentStoreId;

    /**
     * @var HreflangBuilder
     */
    protected $hreflangBuilder;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Page
     */
    protected $cmsPage;

    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LinkFactory
     */
    protected $linkFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Hreflang constructor.
     * @param Context $context
     * @param Registry $registry
     * @param HreflangBuilder $hreflangBuilder
     * @param Page $cmsPage
     * @param StoreRepository $storeRepository
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     * @param LinkFactory $linkFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        HreflangBuilder $hreflangBuilder,
        Page $cmsPage,
        StoreRepository $storeRepository,
        StoreManagerInterface $storeManager,
        Config $config,
        LinkFactory $linkFactory
    ) {
        $this->registry = $registry;
        $this->hreflangBuilder = $hreflangBuilder;
        $this->cmsPage = $cmsPage;
        $this->storeRepository = $storeRepository;
        $this->storeManager = $storeManager;
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->linkFactory = $linkFactory;
        $this->currentStoreId = $this->getCurrentStoreId();
        parent::__construct($context);
    }

    /**
     * Set the hreflang object with all needed data
     *
     * @return bool
     */
    private function setHreflang()
    {
        $fullActionName = $this->getRequest()->getFullActionName();

        return $this->hreflangBuilder
            ->setHreflangObjectByActionName($fullActionName);
    }

    /**
     * Get if Hreflang is enabled
     *
     * @return bool
     */
    public function isHreflangEnabled()
    {
        return $this->config->isHreflangEnabled();
    }

    /**
     * Get all hreflang array to print
     *
     * @return array $hreflangUrls
     */
    public function getHreflangUrls()
    {
        $hreflangUrls = [];

        if ($this->setHreflang()) {
            $storeIds = $this->getStoresToPrintHreflang();
            $type = $this->hreflangBuilder->hreflang->getType();
            $data = $this->setDataToHreflang($type);
            $hreflangUrls['current'] = $this->getCurrentCountryData();

            foreach ($storeIds as $storeId) {
                $countryData = $this->getCountryDataByStoreId($storeId);
                $this->hreflangBuilder->setHreflangData($storeId, $data ?? null);

                if ($equivalentUrl = $this->getEquivalentUrl()) {
                    $link = $this->linkFactory->create();
                    $link->setCountry($countryData['country']);
                    $link->setLanguage($countryData['lang']);
                    $link->setUrl($equivalentUrl);
                    $hreflangUrls[$storeId] = $link;
                }
            }
        }
        return $hreflangUrls;
    }

    /**
     * Setting Data to hreflang object
     *
     * @param $type
     * @return bool|Page|Product|Category
     */
    private function setDataToHreflang($type)
    {
        if ($type == self::PRODUCT) {
            return $this->getProduct();
        }

        if ($type == self::CATEGORY) {
            return $this->getCategory();
        }

        if ($type == self::CMS || $type == self::HOMEPAGE) {
            return $this->getCmsPage();
        }

        return false;
    }

    /**
     * Get current product
     *
     * @return Product|boolean $product
     */
    private function getProduct()
    {
        $product = $this->registry->registry('product');

        if (!$product->getId()) {
            return false;
        }

        return $product;
    }

    /**
     * Get current category
     *
     * @return Category|boolean $category
     */
    private function getCategory()
    {
        $category = $this->registry->registry('current_category');

        if (!$category->getId()) {
            return false;
        }

        return $category;
    }

    /**
     * Get CMS page
     *
     * @return Page
     */
    private function getCmsPage()
    {
        return $this->cmsPage;
    }

    /**
     * Get stores id needed to print hreflang
     *
     * @return int[] $storeIds
     */
    private function getStoresToPrintHreflang()
    {
        $storesList = $this->storeRepository->getList();
        $storeIds = [];

        foreach ($storesList as $store) {
            if ($store['store_id']!=0) {
                $storeIds[] = $store['store_id'];
            }
        }

        return $storeIds;
    }

    /**
     * Get current store id
     *
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get current country data
     *
     * @return array $currentData
     */
    private function getCurrentCountryData()
    {
        $currentData = [];
        $locale = $this->config->getLocaleCode($this->getCurrentStoreId());
        $currentData['country'] = substr($locale, 3, 2);
        $currentData['lang'] = substr($locale, 0, 2);

        return $currentData;
    }

    /**
     * Get language and country data per store Id
     *
     * @param int $storeId
     * @return array $countryData
     */
    private function getCountryDataByStoreId($storeId)
    {
        $countryData = [];
        $locale = $this->config->getLocaleCode($storeId);
        $countryData['lang'] = strtolower(str_replace('_', '-', $locale));
        $countryData['country'] = substr($locale, 0, 2);
        if ($storeId == 6){ // hotfix for nl-nl
            $countryData['country'] = 'nl-nl';
        }
        if ($storeId == 7){ // hotfix for nl-be
            $countryData['country'] = 'nl-be';
        }


        return $countryData;
    }

    /**
     * Get equivalent URL
     *
     * @return string
     */
    private function getEquivalentUrl()
    {
        return $this->hreflangBuilder->getEquivalentUrl();
    }
}
