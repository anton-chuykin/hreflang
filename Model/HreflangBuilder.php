<?php
namespace Kg\Hreflang\Model;

use Kg\Hreflang\Model\Hreflang\EquivalentUrlInterface;

class HreflangBuilder
{
    const CATEGORY_PAGE = 'catalog_category_view';
    const PRODUCT_PAGE = 'catalog_product_view';
    const HOME_PAGE = 'cms_index_index';
    const CMS_PAGE = 'cms_page_view';
    const CONTACT_PAGE = 'contact_index_index';

    /**
     * @var $hreflangFactory
     */
    protected $hreflangFactory;

    /**
     * @var $hreflang
     */
    public $hreflang;

    /**
     * @var array
     */
    private $pageHreflang = [];

    /**
     * HreflangBuilder constructor.
     * @param HreflangFactory $hreflangFactory
     */
    public function __construct(
        HreflangFactory $hreflangFactory
    ) {
        $this->hreflangFactory = $hreflangFactory;
        $this->pageHreflang = [
            self::PRODUCT_PAGE => HreflangFactory::HREFLANG_PRODUCT,
            self::CATEGORY_PAGE => HreflangFactory::HREFLANG_CATEGORY,
            self::HOME_PAGE => HreflangFactory::HREFLANG_HOMEPAGE,
            self::CMS_PAGE => HreflangFactory::HREFLANG_CMS,
            self::CONTACT_PAGE => HreflangFactory::HREFLANG_CONTACTS,
        ];
    }

    /**
     * Set hreflang proper object
     *
     * @param string $actionName
     * @return boolean
     */
    public function setHreflangObjectByActionName($actionName)
    {
        if (!isset($this->pageHreflang[$actionName])) {
            $hreflang = $this->hreflangFactory
                ->getHreflangObject('default');
        } else {
            $hreflang = $this->hreflangFactory
                ->getHreflangObject($this->pageHreflang[$actionName]);
        }

        if (!empty($hreflang)) {
            $this->setHreflang($hreflang);

            return true;
        }

        return false;
    }

    /**
     * Set Hreflang Object
     *
     * @param EquivalentUrlInterface $hreflang
     */
    public function setHreflang(EquivalentUrlInterface $hreflang)
    {
        $this->hreflang = $hreflang;
    }

    /**
     * Set hreflang Data
     *
     * @param $storeId
     * @param $object
     */
    public function setHreflangData($storeId, $object = null)
    {
        $this->hreflang->setStoreId($storeId);

        if ($object) {
            $this->hreflang->setObject($object);
        }
    }

    /**
     * Get equivalent url
     *
     * @return string
     */
    public function getEquivalentUrl()
    {
        $result = $this->hreflang->getEquivalentUrl();
        list($cropUrl) = explode('?', $result);
        return $cropUrl;
    }
}
