<?php

namespace PalashAs\AskQuestion\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Framework\App\Area;

class CustomQtyUpdate extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockStateInterface;

    /**
     * @var Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_stockRegistry;

    /**
     * CustomQtyUpdate constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\State $state
    )
    {
        $this->productRepository = $productRepository;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_stockRegistry = $stockRegistry;
        $this->state = $state;

        parent::__construct();
    }

    /**
     * @param $productId
     * @param $stockData
     * @throws \Exception
     */
    public function updateProductStock($productId, $stockData) {
        $product = $this->productRepository->getById($productId);
        $stockItem = $this->_stockRegistry->getStockItem($product->getId());
        $stockItem->setData('is_in_stock',1);
        $stockItem->setData('qty',$stockData);
        $stockItem->setData('manage_stock',1);
        $stockItem->setData('use_config_notify_stock_qty',1);
        $stockItem->save();
        $this->_stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
    }

    protected function configure()
    {
        $this->setName('product-updater:update-qty')
            ->setDescription('Update Product Qty')
            ->setDefinition([
                new InputArgument(
                    'product_id',
                    InputArgument::OPTIONAL,
                    'Product Id'
                ),
                new InputArgument(
                    'qty',
                    InputArgument::OPTIONAL,
                    'Product Qty'
                )
            ]);

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(Area::AREA_ADMINHTML);

        if ($input->getArgument('product_id')) {
            $productId = $input->getArgument('product_id');
        } else {
            $output->writeln("<info>Please input product Id for update!<info>");
            return;
        }
        if ($input->getArgument('qty') && is_numeric($input->getArgument('qty')) && ($input->getArgument('qty') >= 0)) {
            $qty = $input->getArgument('qty');
        } else {
            $output->writeln("<info>Please input product qty for update!<info>");
            return;
        }
        $output->writeln("<info>Processing product id: $productId. Qty: $qty. <info>");

        $this->updateProductStock($productId, $qty);

        $output->writeln("<info>Product has been updated. <info>");
    }
}
