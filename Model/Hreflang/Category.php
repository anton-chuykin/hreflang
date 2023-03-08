<?php
namespace Kg\Hreflang\Model\Hreflang;

use Amasty\ShopbySeo\Helper\Url as SeoHelper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\StoreManagerInterface;

class Category extends Url implements EquivalentUrlInterface
{
    const TYPE = 'CATEGORY';

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SeoHelper
     */
    private $seoHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Http
     */
    private $request;

    /**
     * Category constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     * @param DataPersistorInterface $dataPersistor
     * @param SeoHelper $seoHelper
     * @param Http $request
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        SeoHelper $seoHelper,
        Http $request
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        $this->seoHelper = $seoHelper;
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
    }

    /**
     * Get equivalent category URL for a given category id and storeId
     * @return mixed|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getEquivalentUrl()
    {
        $params = $this->getMeaningfulParams();

        $url = $this->storeManager->getStore($this->storeId)->getBaseUrl() . $this->categoryRepository
            ->get(
                $this->object->getId(),
                $this->storeId
            )->getUrlPath();
        if (count($params)) {
            $url .= '?' . http_build_query($params);
        }
        $this->dataPersistor->set('shopby_switcher_store_id', $this->storeId);
        $newUrl = $this->seoHelper->seofyUrl($url);
        $this->dataPersistor->clear('shopby_switcher_store_id');
        return $newUrl;
    }

    private function getMeaningfulParams()
    {
        $params = $this->request->getParams();
        if (array_key_exists('id', $params)) {
            unset($params['id']);
        }
        if (array_key_exists('avg', $params)) {
            unset($params['avg']);
        }
        if (array_key_exists('am_base_price', $params)) {
            unset($params['am_base_price']);
        }
        return $params;
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
