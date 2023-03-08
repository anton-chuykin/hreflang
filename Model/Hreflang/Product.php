<?php
namespace Kg\Hreflang\Model\Hreflang;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\CacheInterface;

class Product extends Url implements EquivalentUrlInterface
{
    const TYPE = 'PRODUCT';

    private ProductRepositoryInterface $productRepository;

    private CacheInterface $cache;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CacheInterface $cache
    ) {
        $this->productRepository = $productRepository;
        $this->cache = $cache;
    }

    /**
     * Get equivalent product URL for a given product id and storeId
     *
     * @return string|boolean $equivalentUrl
     */
    public function getEquivalentUrl()
    {
        $equivalentUrl = $this->cache->load('product-url-' . $this->object->getId() . '-' . $this->storeId);
        if ($equivalentUrl) {
            return $equivalentUrl;
        }

        $eqProduct = $this->productRepository
            ->getById(
                $this->object->getId(),
                false,
                $this->storeId,
                false
            );

        if (in_array($this->storeId, $eqProduct->getStoreIds())) {
            $equivalentUrl = $eqProduct->getProductUrl();
        }

        return $equivalentUrl;
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
