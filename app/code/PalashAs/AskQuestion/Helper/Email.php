<?php

namespace PalashAs\AskQuestion\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Email extends AbstractHelper
{
    /**
     * Config path to module enabled emails sending.
     */
    const XML_PATH_ASK_QUESTION_ENABLED_EMAIL_SENDING = 'askquestion_options/general/enable_emails_sending';

    /**
     * Config path to customer support email.
     */
    const XML_PATH_STORE_SUPPORT_EMAIL = 'trans_email/ident_support/email';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Email constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @param string $customerEmail
     * @param $message
     * @param string $customerName
     *
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendMail($customerEmail, $message, $customerName = ''): void
    {
        if ($this->getStoreSupportEmail()) {
            $templateVars = [
                'store' => $this->storeManager->getStore(),
                'title' => __('Hello ') . $customerName,
                'message'   =>  __('Your request was submitted. We\'ll get in touch with you as soon as possible.')
            ];

            $from = ['email' => $this->getStoreSupportEmail(), 'name' => 'Customer Support'];
            $to = [$customerEmail];
            $this->send($from, $to, $templateVars);
            unset($templateVars, $from, $to);
            $templateVars = [
                'store' => $this->storeManager->getStore(),
                'title' => __('Hello Admin'),
                'message'   => __('New Question from customer: ') . $message
            ];

            $from = ['email' => $customerEmail, 'name' => $customerName];
            $to = [$this->getStoreSupportEmail()];
            $this->send($from, $to, $templateVars);
        }
    }

    /**
     * @param $from
     * @param $to
     * @param $templateVars
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function send($from, $to, $templateVars): void
    {
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $this->storeManager->getStore()->getId()
        ];

        $this->inlineTranslation->suspend();
        $transport = $this->transportBuilder->setTemplateIdentifier('ask_question_email_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * @return boolean
     */
    public function isEnabledEmailsSending(): bool
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ASK_QUESTION_ENABLED_EMAIL_SENDING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getStoreSupportEmail(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_STORE_SUPPORT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }
}
