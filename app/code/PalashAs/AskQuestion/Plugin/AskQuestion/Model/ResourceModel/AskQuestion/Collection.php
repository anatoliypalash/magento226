<?php

namespace PalashAs\AskQuestion\Plugin\AskQuestion\Model\ResourceModel\AskQuestion;

use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion\Collection as ProductCollection;

class Collection
{
    /**
     * Collection constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @param ProductCollection $subject
     * @param \Closure $proceed
     * @param bool $printQuery
     * @param bool $logQuery
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundLoad(ProductCollection $subject, \Closure $proceed, $printQuery = false, $logQuery = false)
    {
        $storeId = (int) $this->storeManager->getStore()->getStoreId();
        $subject->addFieldToFilter('store_id', $storeId);
        $result = $proceed($printQuery, $logQuery);

        return $result;
    }

}
