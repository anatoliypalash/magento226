<?php

namespace PalashAs\AskQuestion\Model\ResourceModel\AskQuestion;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \PalashAs\AskQuestion\Model\AskQuestion::class,
            \PalashAs\AskQuestion\Model\ResourceModel\AskQuestion::class
        );
    }
}
