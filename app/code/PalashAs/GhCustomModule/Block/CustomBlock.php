<?php
namespace PalashAs\GhCustomModule\Block;

class CustomBlock extends \Magento\Framework\View\Element\Template
{
    public function getGeneratedUrlToController()
    {
        return $this->getUrl('home-work/index/jsonresponse');
    }
}