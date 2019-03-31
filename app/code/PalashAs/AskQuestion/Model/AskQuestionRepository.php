<?php

namespace PalashAs\AskQuestion\Model;

use PalashAs\AskQuestion\Api\AskQuestionRepositoryInterface;
use PalashAs\AskQuestion\Api\Data\AskQuestionInterface;
use PalashAs\AskQuestion\Api\Data\AskQuestionSearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use PalashAs\AskQuestion\Api\Data\AskQuestionSearchResultsInterfaceFactory;
use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\Collection;
use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion as ResourceAskQuestion;
use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory as AskQuestionCollectionFactory;

/**
 * Class AskQuestionRepository
 *
 */
class AskQuestionRepository implements AskQuestionRepositoryInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ResourceAskQuestion
     */
    private $resource;

    /**
     * @var AskQuestionFactory
     */
    private $askQuestionFactory;

    /**
     * @var AskQuestionCollectionFactory
     */
    private $askQuestionCollectionFactory;

    /**
     * @var AskQuestionSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * AskQuestionRepository constructor.
     * @param ResourceAskQuestion $resource
     * @param AskQuestionFactory $askQuestionFactory
     * @param AskQuestionCollectionFactory $askQuestionCollectionFactory
     * @param AskQuestionSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceAskQuestion $resource,
        AskQuestionFactory $askQuestionFactory,
        AskQuestionCollectionFactory $askQuestionCollectionFactory,
        AskQuestionSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->askQuestionFactory = $askQuestionFactory;
        $this->askQuestionCollectionFactory = $askQuestionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save AskQuestion data
     *
     * @param AskQuestionInterface $askQuestion
     * @return AskQuestionInterface
     * @throws CouldNotSaveException
     */
    public function save(AskQuestionInterface $askQuestion): AskQuestionInterface
    {
        try {
            /** @var AskQuestion $askQuestion */
            $this->resource->save($askQuestion);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $askQuestion;
    }

    /**
     * Retrieve question.
     *
     * @param int $askQuestionId
     * @return AskQuestionInterface
     * @throws LocalizedException
     */
    public function getById($askQuestionId): AskQuestionInterface
    {
        /** @var AskQuestion $askQuestion */
        $askQuestion = $this->askQuestionFactory->create();
        $this->resource->load($askQuestion, $askQuestionId);
        if (!$askQuestion->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $askQuestionId));
        }
        return $askQuestion;
    }

    /**
     * Retrieve questions matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return AskQuestionSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->askQuestionCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        /** @var AskQuestionSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * Delete question.
     *
     * @param AskQuestionInterface $askQuestion
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(AskQuestionInterface $askQuestion): bool
    {
        try {
            /** @var AskQuestion $askQuestion */
            $this->resource->delete($askQuestion);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the question: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete question by ID.
     *
     * @param int $askQuestionId
     * @return bool true on success
     * @throws LocalizedException
     */
    public function deleteById($askQuestionId): bool
    {
        return $this->delete($this->getById($askQuestionId));
    }

}
