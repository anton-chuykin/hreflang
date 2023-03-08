<?php
namespace Kg\Hreflang\Model\ResourceModel;

class CmsRelationship extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * CmsRelationship _construct
     */
    protected function _construct()
    {
        $this->_init('tpw_cms_page_relationship', 'page_id');
        $this->_isPkAutoIncrement = false;
    }
}
