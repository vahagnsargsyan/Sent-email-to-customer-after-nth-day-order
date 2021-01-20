<?php
namespace Yota\Order\Cron;

use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Psr\Log\LoggerInterface;

class Run
{
    protected $_logger;
    protected $_orderCollectionFactory;
    protected $transportBuilder;
    protected $logger;



    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->transportBuilder = $objectManager->create('Magento\Framework\Mail\Template\TransportBuilder');

        $sender = "info@test.com";
        foreach($this->getOrders() as $order){
            try{
                $transport = $this->transportBuilder->setTemplateIdentifier(
                    "sent_email_to_customer_after_14day_email_template") //$this->helperData->getModuleConfig('low_stock_notification/email/email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                    ->setTemplateVars(
                        [
                    'lowStockHtml' => "asd"
                        ]
                    )
                    ->setFrom(
                        [
                            'name' => 'Realchems',
                            'email' => $sender,
                        ]
                    )->addTo(
                        $order->getCustomerEmail()
                    )->getTransport();
                 $this->addEmailStatus($order->getId());
                 $transport->sendMessage();

                } catch (\Exception $e) {
//                     $this->_logger->info($e->getMessage());
                }
        }
        return $this;
    }

    private function addEmailStatus($orderId){
        try{
            $resources = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\App\ResourceConnection');
            $connection= $resources->getConnection();

            $themeTable = $resources->getTableName('email_to_customer_after_14day');
            $sql = "INSERT INTO " . $themeTable . "(order_id, status) VALUES (".$orderId.",1)";
            $connection->query($sql);
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }

    public function getOrders()
    {
        $this->_orderCollectionFactory =  \Magento\Framework\App\ObjectManager::getInstance()
            ->get('\Magento\Sales\Model\ResourceModel\Order\CollectionFactory');
        $to = date("Y-m-d h:i:s");
        $from = strtotime('-14 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from);

        $collection = $this->_orderCollectionFactory->create()->addAttributeToSelect('*');
        $collection->addFieldToFilter('main_table.created_at', array('from'=>$from, 'to'=>$to))
            ->addAttributeToFilter('state', 'complete')->setPageSize(100);

        $joinConditions = 'main_table.entity_id = email_to_customer_after_14day.order_id';
        $collection->addAttributeToSelect('*');
        $collection->getSelect()->joinLeft(
            ['email_to_customer_after_14day'],
            $joinConditions,
            []
        )->where("email_to_customer_after_14day.order_id is null");

        return $collection;
    }
}