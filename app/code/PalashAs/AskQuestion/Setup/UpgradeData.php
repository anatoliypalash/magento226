<?php

namespace PalashAs\AskQuestion\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Store\Model\Store;
use PalashAs\AskQuestion\Model\AskQuestion;
use Magento\Framework\Component\ComponentRegistrar;
use \Magento\Framework\File\Csv;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \PalashAs\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     */
    private $askQuestionFactory;

    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;

    /**
     * UpgradeData constructor.
     * @param \PalashAs\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     * @param ComponentRegistrar $componentRegistrar
     * @param Csv $csv
     */
    public function __construct(
        \PalashAs\AskQuestion\Model\AskQuestionFactory $askQuestionFactory,
        ComponentRegistrar $componentRegistrar,
        Csv $csv
    )
    {
        $this->componentRegistrar = $componentRegistrar;
        $this->csv = $csv;
        $this->askQuestionFactory = $askQuestionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '0.1.1') < 0) {
            $this->updateDataForRequestSample($setup, 'import_data.csv');
        }
        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param $fileName
     * @throws \Exception
     */
    public function updateDataForRequestSample(ModuleDataSetupInterface $setup, $fileName)
    {
        $tableName = $setup->getTable('palashas_askquestion');
        $file_path = $this->getPathToCsvMagentoAtdec($fileName);
        $csvData = $this->csv->getData($file_path);
        if ($setup->getConnection()->isTableExists($tableName) == true) {
            foreach ($csvData as $row => $data) {
                if (count($data) == 9) {
                    $res = $this->getCsvData($data);
                    $setup->getConnection()->insertOnDuplicate(
                        $tableName, $res,
                        [
                            'name',
                            'email',
                            'phone',
                            'product_name',
                            'sku',
                            'question',
                            'created_at',
                            'status',
                            'store_id',
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param $fileName
     * @return string
     */
    private function getPathToCsvMagentoAtdec($fileName)
    {
        return $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'PalashAs_AskQuestion') .
            DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param $data
     * @return array
     */
    private function getCsvData($data)
    {
        return [
            'name' => $data[0],
            'email' => $data[1],
            'phone' => $data[2],
            'product_name' => $data[3],
            'sku' => $data[4],
            'question' => $data[5],
            'created_at' => $data[6],
            'status' => $data[7],
            'store_id' => $data[8],
        ];
    }
}
