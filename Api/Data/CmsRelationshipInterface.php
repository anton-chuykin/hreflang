<?php
namespace Kg\Hreflang\Api\Data;

interface CmsRelationshipInterface
{
    const PAGE_ID = 'page_id';
    const PARENT_ID = 'parent_id';

    /**
     * Get Page ID
     *
     * @return int|null
     */
    public function getPageId();

    /**
     * Get Parent ID
     *
     * @return int|null
     */
    public function getParentId();

    /**
     * Set Page ID
     *
     * @param int $id
     * @return CmsRelationshipInterface
     */
    public function setPageId($id);

    /**
     * Set Parent ID
     *
     * @param int $id
     * @return CmsRelationshipInterface
     */
    public function setParentId($id);
}
