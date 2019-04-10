<?php

namespace PalashAs\AskQuestion\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use PalashAs\AskQuestion\Model\AskQuestion;

class Status implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Answered'),
                'value' => AskQuestion::STATUS_ANSWERED,
            ],
            [
                'label' => __('Pending'),
                'value' => AskQuestion::STATUS_PENDING,
            ],
        ];
    }
}
