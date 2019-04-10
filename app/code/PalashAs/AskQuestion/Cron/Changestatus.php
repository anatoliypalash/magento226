<?php

namespace PalashAs\AskQuestion\Cron;

use PalashAs\AskQuestion\Api\AskQuestionRepositoryInterface;
use PalashAs\AskQuestion\Api\Data\AskQuestionInterface;
use PalashAs\AskQuestion\Model\Config\Source\ListMode;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use PalashAs\AskQuestion\Model\AskQuestion;

class Changestatus
{
    /**
     * Configuration path to customer Ask a Question cron schedule
     */
    private const XML_PATH_ASK_QUESTION_CRON_SCHEDULE = 'askquestion_options/cron/frequency';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ListMode
     */
    private $listMode;

    /**
     * @var AskQuestionRepositoryInterface
     */
    private $askQuestionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ListMode $listMode
     * @param AskQuestionRepositoryInterface $askQuestionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ListMode $listMode,
        AskQuestionRepositoryInterface $askQuestionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->listMode = $listMode;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->askQuestionRepository = $askQuestionRepository;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): void
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('status', AskQuestion::STATUS_PENDING)->create();
        $questions = $this->askQuestionRepository->getList($searchCriteria);
        foreach ($questions->getItems() as $item) {
            /** @var AskQuestionInterface $question */
            $question = $this->askQuestionRepository->getById($item['question_id']);
            $question->setStatus(AskQuestion::STATUS_PROCESSED);
            $this->askQuestionRepository->save($question);
        }
    }

    /**
     * @return int
     */
    public function getNumberOfDays(): int
    {
        foreach ($this->listMode->toOptionArray() as $key => $item) {
            if ($item ['value'] === $this->getCronSchedule()) {
                return $item ['days'];
            }
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getCronSchedule(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ASK_QUESTION_CRON_SCHEDULE,
            ScopeInterface::SCOPE_STORE
        );
    }
}
