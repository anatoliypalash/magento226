<?php

namespace PalashAs\GhCustomModule\Controller\Index;

class ShowPerson extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->getBlock('show_person')->setName('Anatolii');
        $this->_view->getLayout()->getBlock('show_person')->setLastname('Palash');

        $this->_view->renderLayout();
    }
}
