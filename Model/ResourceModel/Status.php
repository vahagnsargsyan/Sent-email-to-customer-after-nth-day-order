<?php
namespace Yota\Order\Model\ResourceModel;


class Status extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('email_to_customer_after_14day', 'status_id');
    }

}