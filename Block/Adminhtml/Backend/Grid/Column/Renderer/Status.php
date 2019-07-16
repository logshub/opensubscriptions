<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Backend\Grid\Column\Renderer;

use OpenSubscriptions\OpenSubscriptions\Model\Service;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $status = $this->_getValue($row);
        $color = 'black';
        switch ($status){
            case Service::ACTIVE:
                $color = 'green';
                break;
            case Service::DELETED:
            case Service::DISABLED:
                $color = 'red';
                break;
        }
        
        return '<strong style="color:' . $color . ';">' . $status . '</strong>';
    }
}
