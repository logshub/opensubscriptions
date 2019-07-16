<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml\Backend\Grid\Column\Renderer;

use Logshub\OpenSubscriptions\Model\Service;

class ExpireAt extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $expireAt = $this->_getValue($row);
        if (!$expireAt){
            return '';
        }


        $expiresIn = '';
        $dDiff = (new \DateTime())->diff(new \DateTime($expireAt));
        if ($dDiff->invert === 0){
            $color = 'black';
            if ($dDiff->days < 5){
                $color = 'red';
            } elseif ($dDiff->days < 30){
                $color = '#808000';
            }
            $expiresIn = '<strong style="color:'.$color.';">('.$dDiff->days . ' ' . __('days').')</strong>';
        } else {
            $expiresIn = '<strong style="color:red;">(' . __('expired').')</strong>';
        }
        
        return $expiresIn . ' ' . $expireAt;
    }
}
