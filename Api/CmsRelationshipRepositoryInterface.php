<?php
namespace Kg\Hreflang\Api;

interface CmsRelationshipRepositoryInterface
{
    /**
     * @param int $id
     * @return \Kg\Hreflang\Api\Data\CmsRelationshipInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Kg\Hreflang\Api\Data\CmsRelationshipInterface $cmsRelationship
     * @return \Kg\Hreflang\Api\Data\CmsRelationshipInterface
     */
    public function save(Data\CmsRelationshipInterface $cmsRelationship);

    /**
     * @param \Kg\Hreflang\Api\Data\CmsRelationshipInterface $cmsRelationship
     * @return void
     */
    public function delete(Data\CmsRelationshipInterface $cmsRelationship);

    /**
     * @param \Magento\Cms\Model\Page $page
     * @return \Kg\Hreflang\Api\Data\CmsRelationshipInterface
     */
    public function getRelationshipPageId(\Magento\Cms\Model\Page $page);

    /**
     * @param \Magento\Cms\Model\Page $page
     * @param int $storeId
     * @return int[]|boolean
     */
    public function getRelatedStorePageIds(
        \Magento\Cms\Model\Page $page,
        $storeId
    );
}
