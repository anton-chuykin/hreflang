<?php
namespace Kg\Hreflang\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaBuilder as SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Kg\Hreflang\Api\CmsRelationshipRepositoryInterface;
use Kg\Hreflang\Api\Data;
use Kg\Hreflang\Model\ResourceModel\CmsRelationship as ResourceCmsRelation;
use Kg\Hreflang\Model\ResourceModel\CmsRelationship\Collection as CmsRelationCollection;
use Kg\Hreflang\Model\ResourceModel\CmsRelationship\CollectionFactory as CmsRelationCollectionFactory;

class CmsRelationshipRepository implements CmsRelationshipRepositoryInterface
{

    const PAGE_COLUMN = 'page_id';
    const PARENT_PAGE_COLUMN = 'parent_id';
    const NO_RELATION = 0;

    /**
     * @var ResourceCmsCmsRelation
     */
    protected $resource;

    /**
     * @var CmsRelationshipFactory
     */
    protected $cmsRelationshipFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var CmsRelationCollectionFactory
     */
    protected $cmsRelationCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * CmsRelationshipRepository constructor.
     * @param ResourceCmsRelation $resource
     * @param CmsRelationshipFactory $cmsRelationshipFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CmsRelationCollectionFactory $cmsRelationCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceCmsRelation $resource,
        CmsRelationshipFactory $cmsRelationshipFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CmsRelationCollectionFactory $cmsRelationCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->cmsRelationshipFactory = $cmsRelationshipFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cmsRelationCollectionFactory = $cmsRelationCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Load CmsRelationship by Page id
     *
     * @param int $pageId
     * @return bool|Data\CmsRelationshipInterface
     */
    public function getById($pageId)
    {
        $cmsRelation = $this->cmsRelationshipFactory->create();
        $this->resource->load($cmsRelation, $pageId);

        if (!$cmsRelation->getId()) {
            return false;
        }
        return $cmsRelation;
    }

    /**
     * Save Cms Relationship
     * @param Data\CmsRelationshipInterface $cmsRelationship
     * @return Data\CmsRelationshipInterface $cmsRelationship
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function save(Data\CmsRelationshipInterface $cmsRelationship)
    {
        try {
            $this->resource->save($cmsRelationship);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $cmsRelationship;
    }

    /**
     * Delete Cms Relationship
     *
     * @param Data\CmsRelationshipInterface $cmsRelationship
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\CmsRelationshipInterface $cmsRelationship)
    {
        try {
            $this->resource->delete($cmsRelationship);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Get parent page Id
     *
     * @param \Magento\Cms\Model\Page $page
     * @return int
     */
    public function getRelationshipPageId(\Magento\Cms\Model\Page $page)
    {
        $pageStores = $page->getStores();
        $pageStoreId = reset($pageStores);

        if (!$pageStoreId || $this->isDefaultStoreView($pageStoreId)) {
            return $page->getId();
        }

        $relationshipPage = $this->getById($page->getId());

        if ($relationshipPage) {
            return $relationshipPage->getParentId();
        }

        return self::NO_RELATION;
    }

    /**
     * Get related pages for store id
     *
     * @param \Magento\Cms\Model\Page $page
     * @param int $storeId
     * @return int[]|boolean
     */
    public function getRelatedStorePageIds(
        \Magento\Cms\Model\Page $page,
        $storeId
    ) {
        $collection = $this->cmsRelationCollectionFactory->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                self::PARENT_PAGE_COLUMN,
                $this->getRelationshipPageId($page),
                'eq'
            )->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection->load();

        if ($collection->getSize()) {
            return $this->getPageIdsFromRelation($collection);
        }

        return false;
    }

    /**
     * Check if store id is default view
     *
     * @param int $storeId
     * @return bool
     */
    private function isDefaultStoreView($storeId)
    {
        return (int)$storeId === (int)$this->storeManager
                ->getDefaultStoreView()->getStoreId();
    }

    /**
     * Get Page Ids array from CmsRelation
     * @param CmsRelationCollection $cmsRelationshipCollection
     * @return int[] $pageIds
     */
    private function getPageIdsFromRelation(
        CmsRelationCollection $cmsRelationshipCollection
    ) {
        $pageIds = [];

        foreach ($cmsRelationshipCollection as $cmsRelationship) {
            $pageIds[] = $cmsRelationship->getData(self::PAGE_COLUMN);
        }

        return $pageIds;
    }
}
