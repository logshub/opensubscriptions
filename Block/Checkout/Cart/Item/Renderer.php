<?php
namespace Logshub\OpenSubscriptions\Block\Checkout\Cart\Item;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{
    /**
     * Overriding from \Magento\Framework\View\Element\Template
     */
    public function getTemplate()
    {
        /** @var \Magento\Quote\Model\Quote\Item\AbstractItem */
        $item = $this->getItem();
        if ($item){
            $product = $item->getProduct();
            if ($product->getOpenSubscriptionsSubmodule()) {
                return 'Logshub_OpenSubscriptions::checkout/cart/item/default.phtml';
            }
        }

        return 'Magento_Checkout::cart/item/default.phtml';
    }
}