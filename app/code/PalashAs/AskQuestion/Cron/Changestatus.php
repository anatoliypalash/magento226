<?php

namespace PalashAs\AskQuestion\Cron;

use PalashAs\AskQuestion\Model\AskQuestion;

class Changestatus
{
    protected $questionsFactory;

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
        PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $askQuestionsFactory
    )
    {
        $this->logger = $logger;
        $this->questionsFactory = $askQuestionsFactory;
    }

    public function execute()
    {
        $questions = $this->questionsFactory->create();
        $collection = $questions->getCollection();

        foreach ($collection as $item) {
            $item->setStatus(AskQuestion::STATUS_ANSWERED)->save();
        }

    }

}
