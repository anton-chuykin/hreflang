<?php

namespace Kg\Hreflang\Model\Hreflang;

use Magento\Cms\Model\PageRepository;
use Magento\Store\Model\StoreManagerInterface;
use Kg\Hreflang\Model\CmsRelationshipRepository;

class Cms extends Url implements EquivalentUrlInterface
{
    const TYPE = 'CMS';

    private $cmsArray = [
        'about-us' => [
            '1' => 'about-us',
            '2' => 'about-us',
            '3' => 'about-us',
            '4' => 'about-us',
            '5' => 'about-us',
            '6' => 'about-us',
            '7' => 'about-us'
        ],
        'shipping' => [
            '1' => 'shipping',
            '2' => 'lieferadresse',
            '3' => 'infomace-o-preprave',
            '4' => 'consegna-e-spedizioni',
            '5' => 'informations-sur-la-livraison',
            '6' => 'verzendinformatie',
            '7' => 'verzendinformatie',
        ],
        'refund-policy' => [
            '1' => 'refund-policy',
            '2' => 'ruckgabe-und-ruckerstattung',
            '3' => 'refund-policy',
            '4' => 'refund-policy',
            '5' => 'refund-policy',
            '6' => 'refund-policy',
            '7' => 'refund-policy'
        ],
        'privacy' => [
            '1' => 'privacy',
            '2' => 'datenschutz',
            '3' => 'privacy',
            '4' => 'privacy',
            '5' => 'privacy',
            '6' => 'privacy',
            '7' => 'privacy'
        ],
        'terms-of-services' => [
            '1' => 'terms-of-services',
            '2' => 'agb',
            '3' => 'terms-of-services',
            '4' => 'terms-of-services',
            '5' => 'terms-of-services',
            '6' => 'terms-of-services',
            '7' => 'terms-of-services'
        ]

    ];

    /**
     * @var CmsRelationshipRepository
     */
    private $cmsRelationshipRepository;

    /**
     * @var PageRepository $pageRepository
     */
    private $pageRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Cms constructor.
     * @param CmsRelationshipRepository $cmsRelationshipRepository
     * @param PageRepository $pageRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CmsRelationshipRepository $cmsRelationshipRepository,
        PageRepository $pageRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->cmsRelationshipRepository = $cmsRelationshipRepository;
        $this->pageRepository = $pageRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Get equivalent Cms URL for a given CMS id and storeId
     *
     * @return string|boolean
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getEquivalentUrl()
    {
        $currentStoreId = $this->storeManager->getStore()->getStoreId();
        $currentUrlKey = $this->object->getIdentifier();
        $newStoreId = $this->storeId;

        foreach ($this->cmsArray as $cmsItems) {
            if (array_search($currentUrlKey, $cmsItems)) {
                foreach ($cmsItems as $key => $cmsItem) {
                    if ($key == $currentStoreId && $currentUrlKey == $cmsItem) {
                        return $this->storeManager->getStore($newStoreId)->getBaseUrl() . $cmsItems[$newStoreId];
                    }
                }
            }
        }

        return $this->storeManager->getStore($newStoreId)->getBaseUrl() . $currentUrlKey;
    }

    /**
     * Get pageurl of this store, by english url key
     *
     * @param $idenfifier
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrlByIdentifier($idenfifier)
    {
        $currentStoreId = $this->storeManager->getStore()->getStoreId();
        $baseUrl = $this->storeManager->getStore($currentStoreId)->getBaseUrl();
        if (array_key_exists($idenfifier, $this->cmsArray)) {
            return $baseUrl . $this->cmsArray[$idenfifier][$currentStoreId];
        } else {
            return $baseUrl;
        }
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
