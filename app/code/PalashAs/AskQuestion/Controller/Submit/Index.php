<?php
namespace PalashAs\AskQuestion\Controller\Submit;

use PalashAs\AskQuestion\Model\AskQuestion;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Index extends \Magento\Framework\App\Action\Action
{
    const STATUS_ERROR = 'Error';

    const STATUS_SUCCESS = 'Success';

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \PalashAs\AskQuestion\Model\AskQuestionFactory
     */
    private $askQuestionFactory;

    /**
     * @var \PalashAs\AskQuestion\Helper\Email
     */
    protected $emailHelper;

    /**
     * Index constructor.
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \PalashAs\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     * @param \Magento\Framework\App\Action\Context $context
     * @param \PalashAs\AskQuestion\Helper\Email $emailHelper
     */
    public function __construct(
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \PalashAs\AskQuestion\Model\AskQuestionFactory $askQuestionFactory,
        \Magento\Framework\App\Action\Context $context,
        \PalashAs\AskQuestion\Helper\Email $emailHelper
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->askQuestionFactory = $askQuestionFactory;
        $this->emailHelper = $emailHelper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        /** @var Http $request */
        $request = $this->getRequest();
        try {
            if (!$this->formKeyValidator->validate($request)) {
                throw new LocalizedException(__('Something went wrong. Probably you were away for quite a long time already. Please, reload the page and try again.'));
            }
            if (!$request->isAjax()) {
                throw new LocalizedException(__('This request is not valid and can not be processed.'));
            }

            if ($request->getParam('ask_question_timestamp') == '1') {
                $data = [
                    'status' => self::STATUS_ERROR,
                    'message' => 'Your request cannot be submitted.'
                ];
            } else {
                /** @var AskQuestion $askQuestion */
                $askQuestion = $this->askQuestionFactory->create();
                $askQuestion->setName($request->getParam('name'))
                    ->setEmail($request->getParam('email'))
                    ->setPhone($request->getParam('phone'))
                    ->setProductName($request->getParam('product_name'))
                    ->setSku($request->getParam('sku'))
                    ->setQuestion($request->getParam('question'));
                $askQuestion->save();

                if ($this->emailHelper->isEnabledEmailsSending()) {
                    $email = $askQuestion->getEmail();
                    $customerName = $askQuestion->getName();
                    $message = $request->getParam('question');
                    $this->emailHelper->sendMail($email, $message, $customerName);
                }

                $data = [
                    'status' => self::STATUS_SUCCESS,
                    'message' => 'Your request was submitted.'
                ];
            }
        } catch (LocalizedException $e) {
            $data = [
                'status'  => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ];
        }
        /**
         * @var \Magento\Framework\Controller\Result\Json $controllerResult
         */
        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $controllerResult->setData($data);
    }

}
