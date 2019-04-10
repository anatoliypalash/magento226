<?php

namespace PalashAs\AskQuestion\Model\Config\Backend;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Config value backend model.
 */
class Cron extends Value
{
    /**
     * Cron schedule
     */
    const CRON_SCHEDULE_PATH = 'crontab/default/jobs/ask_question/schedule/cron_expr';

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param WriterInterface $configWriter
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        WriterInterface $configWriter,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->configWriter = $configWriter;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     *
     * {@inheritdoc}. Set schedule setting for cron.
     *
     * @return Value
     * @throws LocalizedException
     */
    public function afterSave()
    {
        $cronExprString = $this->getValue();
        try {
            $this->configWriter->save(self::CRON_SCHEDULE_PATH, $cronExprString);
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            throw new LocalizedException(__('Cron config can\'t be saved'));
        }
        return parent::afterSave();
    }
}
