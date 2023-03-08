<?php
namespace Kg\Hreflang\Model\ResourceModel\CmsRelationship;

use Kg\Hreflang\Model\CmsRelationship as CmsRelationshipModel;
use Kg\Hreflang\Model\ResourceModel\CmsRelationship as CmsRelationshipResourceModel;

/**
 * Class Kg\Hreflang\Model\ResourceModel\CmsRelationship;
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            CmsRelationshipModel::class,
            CmsRelationshipResourceModel::class
        );
    }
}
