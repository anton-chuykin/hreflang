<?php
namespace Kg\Hreflang\Plugin;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Kg\Hreflang\Model\CmsRelationship as CmsRelationship;
use Kg\Hreflang\Model\CmsRelationshipFactory as CmsRelationshipFactory;
use Kg\Hreflang\Model\CmsRelationshipRepository as CmsRelationshipRepository;

class PagePlugin
{
    /**
     * @var $cmsRelationship
     */
    private $cmsRelationship;

    /**
     * @var CmsRelationshipFactory
     */
    private $cmsRelationshipFactory;

    /**
     * @var CmsRelationshipRepository
     */
    private $cmsRelationshipRepository;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepositoryInterface;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * PagePlugin constructor.
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CmsRelationship $cmsRelationship
     * @param CmsRelationshipFactory $cmsRelationshipFactory
     * @param CmsRelationshipRepository $cmsRelationshipRepository
     */
    public function __construct(
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CmsRelationship $cmsRelationship,
        CmsRelationshipFactory $cmsRelationshipFactory,
        CmsRelationshipRepository $cmsRelationshipRepository
    ) {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cmsRelationship = $cmsRelationship;
        $this->cmsRelationshipFactory = $cmsRelationshipFactory;
        $this->cmsRelationshipRepository = $cmsRelationshipRepository;
    }

    /**
     * @param \Magento\Cms\Controller\Adminhtml\Page\Save $subject
     * @param \Closure $proceed
     * @param mixed ...$args
     * @return mixed
     */
    public function aroundExecute(
        \Magento\Cms\Controller\Adminhtml\Page\Save $subject,
        \Closure $proceed,
        ...$args
    ) {
        $postData = $subject->getRequest()->getPostValue();

        if (isset($postData['cms_relationship']) && $postData['cms_relationship']) {
            if (isset($postData['page_id'])) {
                $pageId = $postData['page_id'];
            } else {
                $searchCriteria = $this->searchCriteriaBuilder->create();
                $cmsPagesCreated = $this->pageRepositoryInterface
                    ->getList($searchCriteria)->getItems();

                foreach ($cmsPagesCreated as $cmsPage) {
                    $pageId = $cmsPage->getId();
                }
            }

            $cmsRelation = $this->cmsRelationshipRepository->getById($pageId);

            if (!$cmsRelation) {
                $cmsRelation = $this->cmsRelationshipFactory->create();
                $cmsRelation->setPageId($pageId);
            }

            $cmsRelation->setParentId($postData['cms_relationship']);
            $this->cmsRelationshipRepository->save($cmsRelation);
        }

        return $proceed($args);
    }
}
