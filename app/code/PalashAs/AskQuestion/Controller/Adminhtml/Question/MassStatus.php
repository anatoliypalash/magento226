<?php

namespace PalashAs\AskQuestion\Controller\Adminhtml\Question;

use Magento\Backend\App\Action\Context;
use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\Collection;

/**
 * Class MassStatus
 * @package PalashAs\AskQuestion\Controller\Adminhtml\Question
 */
class MassStatus extends AbstractMassAction
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @param Collection $collection
     * @return mixed
     */
    protected function massAction(Collection $collection)
    {
        $questionChangeStatus = 0;
        foreach ($collection as $rate) {
            $rate->setStatus('answered')->save();
            $questionChangeStatus++;
        }

        if ($questionChangeStatus) {
            $this->messageManager->addSuccess(__('A total of %1 record(s) were updated.', $questionChangeStatus));
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}
