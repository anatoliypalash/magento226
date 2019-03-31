<?php

namespace PalashAs\AskQuestion\Model;

use PalashAs\AskQuestion\Api\Data\AskQuestionInterface;
use PalashAs\AskQuestion\Model\ResourceModel\AskQuestion as AskQuestionResource;

/**
 * Class AskQuestion
 * @package PalashAs\AskQuestion\Model
 */
class AskQuestion  extends \Magento\Framework\Model\AbstractModel implements AskQuestionInterface
{
    const STATUS_PENDING = 'pending';

    const STATUS_ANSWERED = 'answered';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var string
     */
    protected $_eventPrefix = 'palashas_ask_question';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(AskQuestionResource::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->getData('question_id');
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData('question_id', $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData('created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData('updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->getData('name');
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData('name', $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return $this->getData('email');
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        return $this->setData('email', $email);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone(): string
    {
        return $this->getData('phone');
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone($phone)
    {
        return $this->setData('phone', $phone);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku(): string
    {
        return $this->getData('sku');
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData('sku', $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestion(): string
    {
        return $this->getData('question');
    }

    /**
     * {@inheritdoc}
     */
    public function setQuestion($question)
    {
        return $this->setData('question', $question);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): ?string
    {
        return $this->getData('status');
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        return $this->setData('store_id', $storeId);
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSave()
    {
        if (!$this->getStatus()) {
            $this->setStatus(self::STATUS_PENDING);
        }
        if (!$this->getStoreId()) {
            $this->setStoreId($this->storeManager->getStore()->getId());
        }
        return parent::beforeSave();
    }
}
