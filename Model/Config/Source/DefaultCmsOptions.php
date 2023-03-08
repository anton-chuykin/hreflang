<?php
namespace Kg\Hreflang\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\Registry;
use Magento\Cms\Model\ResourceModel\Page\Collection as PageCollection;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;

class DefaultCmsOptions implements ArrayInterface
{
    const DEFAULT_VALUE = 0;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\Collection
     */
    private $defaultCmsPagesCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var int $defaultStoreId
     */
    private $defaultStoreId;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * DefaultCmsOptions constructor.
     * @param \Magento\Cms\Model\ResourceModel\Page\Collection $cmsPageCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        PageCollection $cmsPageCollection,
        StoreManagerInterface $storeManager,
        Registry $registry
    ) {
        $this->defaultCmsPagesCollection = $cmsPageCollection;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->setDefaultStoreId();
    }

    /**
     * Get all CMS Pages from default store Id
     *
     * @return array
     */
    public function getDefaultStorePages()
    {
        return $this->defaultCmsPagesCollection
            ->addStoreFilter($this->defaultStoreId);
    }

    /**
     * Set default store id
     */
    private function setDefaultStoreId()
    {
        $this->defaultStoreId = $this->storeManager
            ->getDefaultStoreView()->getStoreId();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $currentCmsPageStores = $this->registry
            ->registry('cms_page')->getStores();
        $stores = reset($currentCmsPageStores);
        $defaultStorePages = $this->getDefaultStorePages();
        $options = [];
        $options[] = [
            'label' => __('Please Choose A Related Page'),
            'value' => self::DEFAULT_VALUE
        ];

        if ($stores && $stores != $this->defaultStoreId) {
            foreach ($defaultStorePages as $defaultStorePage) {
                $data = [
                    'label' => $defaultStorePage->getTitle(),
                    'value' => $defaultStorePage->getId()
                ];
                $options[] = $data;
            }
        }

        return $options;
    }
}
