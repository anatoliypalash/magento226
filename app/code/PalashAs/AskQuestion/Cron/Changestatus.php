<?php

namespace PalashAs\AskQuestion\Cron;

use PalashAs\AskQuestion\Model\AskQuestion;
use Magento\Store\Model\ScopeInterface;

class Changestatus
{
    const DAYS_NUM = 3;

    protected $questionsFactory;

    protected $scopeInterface;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Changestatus constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $askQuestionsFactory
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $askQuestionsFactory,
        ScopeInterface $scopeInterface
    )
    {
        $this->logger = $logger;
        $this->questionsFactory = $askQuestionsFactory;
        $this->scopeInterface = $scopeInterface;
    }

    public function execute()
    {
        $currentDate = date("Y-m-d h:i:s");
        $filterDateTime = strtotime('-' . $this->getNumberOfDays() . ' day', strtotime($currentDate));
        $filterDate = date('Y-m-d h:i:s', $filterDateTime);

        $questions = $this->questionsFactory->create();
        $collection = $questions->getCollection()
            ->addFieldToFilter('status', array('eq' => AskQuestion::STATUS_PENDING))
            ->addFieldToFilter('created_at', array('lt' => $filterDate));

        foreach ($collection as $item) {
            $item->setStatus(AskQuestion::STATUS_ANSWERED)->save();
        }

    }

    protected function getNumberOfDays()
    {
        return $this->scopeInterface->getValue(
            'askquestion_options/cron/frequency', ScopeInterface::SCOPE_STORE, 0
        );
    }

}
