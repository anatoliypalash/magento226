<?php

namespace PalashAs\AskQuestion\Model\Config\Source;

class ListMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => '0 0 * * *',
                'label' => __('Daily'),
                'days' => 1
            ],
            [
                'value' => '0 0 1-30/3 * *',
                'label' => __('Every 3 Days'),
                'days' => 3
            ],
            [
                'value' => '0 0 * * 4',
                'label' => __('Weekly'),
                'days' => 7
            ],
        ];
    }
}
