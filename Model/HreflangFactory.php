<?php

namespace Kg\Hreflang\Model;

use \Kg\Hreflang\Model\Hreflang;

class HreflangFactory
{
    const HREFLANG_PRODUCT = 'product';
    const HREFLANG_CATEGORY = 'category';
    const HREFLANG_CMS = 'cms';
    const HREFLANG_HOMEPAGE = 'homepage';
    const HREFLANG_CONTACTS = 'contacts';

    /**
     * @var Hreflang\ProductFactory
     */
    protected $productFactory;

    /**
     * @var Hreflang\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var Hreflang\CmsFactory
     */
    protected $cmsFactory;

    /**
     * @var Hreflang\HomepageFactory
     */
    protected $homepageFactory;

    /**
     * @var Hreflang\ContactsFactory
     */
    protected $contactsFactory;

    /**
     * @var Hreflang\DefaultUrlFactory
     */
    protected $defaultUrlFactory;

    /**
     * HreflangFactory constructor.
     * @param Hreflang\ProductFactory $productFactory
     * @param Hreflang\CategoryFactory $categoryFactory
     * @param Hreflang\CmsFactory $cmsFactory
     * @param Hreflang\HomepageFactory $homepageFactory
     * @param Hreflang\ContactsFactory $contactsFactory
     * @param Hreflang\DefaultUrlFactory $defaultUrlFactory
     */
    public function __construct(
        Hreflang\ProductFactory $productFactory,
        Hreflang\CategoryFactory $categoryFactory,
        Hreflang\CmsFactory $cmsFactory,
        Hreflang\HomepageFactory $homepageFactory,
        Hreflang\ContactsFactory $contactsFactory,
        Hreflang\DefaultUrlFactory $defaultUrlFactory
    ) {
        $this->productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->cmsFactory = $cmsFactory;
        $this->homepageFactory = $homepageFactory;
        $this->contactsFactory = $contactsFactory;
        $this->defaultUrlFactory = $defaultUrlFactory;
    }

    /**
     * Get Hreflang proper object
     *
     * @param string $option
     * @return \Kg\Hreflang\Model\Hreflang\EquivalentUrlInterface $hreflang
     */
    public function getHreflangObject($option)
    {
        if ($option == self::HREFLANG_CATEGORY) {
            $hreflang = $this->categoryFactory->create();
        }

        if ($option == self::HREFLANG_PRODUCT) {
            $hreflang = $this->productFactory->create();
        }

        if ($option == self::HREFLANG_CMS) {
            $hreflang = $this->cmsFactory->create();
        }

        if ($option == self::HREFLANG_HOMEPAGE) {
            $hreflang = $this->homepageFactory->create();
        }

        if ($option == self::HREFLANG_CONTACTS) {
            $hreflang = $this->contactsFactory->create();
        }

        if (empty($hreflang)) {
            $hreflang = $this->defaultUrlFactory->create();
        }

        return $hreflang;
    }
}
