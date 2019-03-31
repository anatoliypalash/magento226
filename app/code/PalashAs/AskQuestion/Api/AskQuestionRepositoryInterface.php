<?php

namespace PalashAs\AskQuestion\Api;

use PalashAs\AskQuestion\Api\Data\AskQuestionInterface;
use PalashAs\AskQuestion\Api\Data\AskQuestionSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Ask a Question CRUD interface.
 * @api
 */
interface AskQuestionRepositoryInterface
{
    /**
     * Save question.
     *
     * @param \PalashAs\AskQuestion\Api\Data\AskQuestionInterface $askQuestion
     * @return \PalashAs\AskQuestion\Api\Data\AskQuestionInterface
     * @throws LocalizedException
     */
    public function save(AskQuestionInterface $askQuestion);

    /**
     * Retrieve question.
     *
     * @param int $askQuestionId
     * @return \PalashAs\AskQuestion\Api\Data\AskQuestionInterface
     * @throws LocalizedException
     */
    public function getById($askQuestionId);

    /**
     * Retrieve questions matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \PalashAs\AskQuestion\Api\Data\AskQuestionSearchResultsInterface
     *
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete question.
     *
     * @param \PalashAs\AskQuestion\Api\Data\AskQuestionInterface $askQuestion
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(AskQuestionInterface $askQuestion);

    /**
     * Delete question by ID.
     *
     * @param int $askQuestionId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($askQuestionId);
}
