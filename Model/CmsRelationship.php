<?php

namespace Kg\Hreflang\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Kg\Hreflang\Api\Data\CmsRelationshipInterface;
use Kg\Hreflang\Model\ResourceModel\CmsRelationship as CmsRelationshipResourceModel;

class CmsRelationship extends AbstractModel implements IdentityInterface, CmsRelationshipInterface
{
    const CACHE_TAG = 'cms_relationship';

    /**
     * @var string
     */
    protected $_cacheTag = 'cms_relationship';

    /**
     * @var string
     */
    protected $_eventPrefix = 'cms_relationship';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(CmsRelationshipResourceModel::class);
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Page ID
     *
     * @return int|null
     */
    public function getPageId()
    {
        return $this->getData(self::PAGE_ID);
    }

    /**
     * Get Parent ID
     *
     * @return int|null
     */
    public function getParentId()
    {
        return $this->getData(self::PARENT_ID);
    }

    /**
     * Set Page ID
     *
     * @param int $id
     * @return CmsRelationshipInterface
     */
    public function setPageId($id)
    {
        return $this->setData(self::PAGE_ID, $id);
    }

    /**
     * Set Parent ID
     *
     * @param int $id
     * @return CmsRelationshipInterface
     */
    public function setParentId($id)
    {
        return $this->setData(self::PARENT_ID, $id);
    }
}
