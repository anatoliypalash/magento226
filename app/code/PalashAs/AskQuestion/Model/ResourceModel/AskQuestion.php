<?php

namespace PalashAs\AskQuestion\Model\ResourceModel;

class AskQuestion extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('palashas_askquestion', 'question_id');
    }
}
