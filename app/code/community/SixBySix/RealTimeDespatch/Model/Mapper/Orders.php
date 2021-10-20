<?php

use \SixBySix\RealtimeDespatch\Entity\OrderCollection as RTDOrderCollection;
use \SixBySix\RealtimeDespatch\Entity\Order as RTDOrder;
use \SixBySix\RealtimeDespatch\Entity\Product as RTDProduct;
use SixBySix\RealtimeDespatch\Entity\DeliveryAddress as RTDDeliveryAddress;
use SixBySix\RealtimeDespatch\Entity\InvoiceAddress as RTDInvoiceAddress;
use SixBySix\RealtimeDespatch\Entity\OrderLine as RTDOrderLine;
use SixBySix\RealtimeDespatch\Entity\Shipment as RTDShipment;

/**
 * Orders Mapper.
 *
 * Maps between Magento and Real Time Order Models.
 */
class SixBySix_RealTimeDespatch_Model_Mapper_Orders extends Mage_Core_Helper_Abstract
{
    /**
     * Encodes a Magento Order Collection.
     *
     * @param Mage_Sales_Model_Resource_Order_Collection $orders
     * @param string                                  $exportedAt
     *
     * @return RTDProductCollection
     */
    public function encode(Mage_Sales_Model_Resource_Order_Collection $orders, $exportedAt)
    {
        $encodedOrders = new RTDOrderCollection;

        foreach ($orders as $order) {
            $encodedOrders[] = $this->_encodeOrder($order, $exportedAt);
        }

        return $encodedOrders;
    }

    /**
     * Encodes a single Magento Order.
     *
     * @param Mage_Sales_Model_Order $order
     * @param string               $exportedAt
     *
     * @return RTDOrder
     */
    protected function _encodeOrder(Mage_Sales_Model_Order $order, DateTime $exportedAt)
    {
        $encodedOrder  = new RTDOrder;
        $customMapping = Mage::getConfig()->getNode('rtd_mappings/order');

        foreach ($customMapping->asArray() as $magentoKey => $rtdKey) {
            $encodedOrder->setParam($rtdKey, $order->{'get'.$magentoKey}());
        }

        $encodedOrder->setParam('exported', str_replace(' ','T', $exportedAt->format('Y-m-d H:i:s')));

        $this->_encodeShippingAddress(
            $encodedOrder,
            $order->getShippingAddress()
        );

        $this->_encodeBillingAddress(
            $encodedOrder,
            $order->getBillingAddress()
        );

        $this->_encodeOrderLines(
            $encodedOrder,
            $order
        );

        $this->_encodeShipment(
            $encodedOrder,
            $order
        );

        return $encodedOrder;
    }

    /**
     * Encodes the shipping address.
     *
     * @param RTDOrder                       $encodedOrder
     * @param Mage_Sales_Model_Order_Address $shippingAddress
     *
     * @return void
     */
    protected function _encodeShippingAddress($encodedOrder, $shippingAddress)
    {
        $encodedAddress = new RTDDeliveryAddress;
        $customMapping  = Mage::getConfig()->getNode('rtd_mappings/delivery_address');

        foreach ($customMapping->asArray() as $magentoKey => $rtdKey) {
            $encodedAddress->setParam($rtdKey, $shippingAddress->{'get'.$magentoKey}());
        }

        $encodedOrder->setDeliveryAddress($encodedAddress);
    }

    /**
     * Encodes the billing address.
     *
     * @param RTDOrder                       $encodedOrder
     * @param Mage_Sales_Model_Order_Address $billingAddress
     *
     * @return void
     */
    protected function _encodeBillingAddress(RTDOrder $encodedOrder, Mage_Sales_Model_Order_Address $billingAddress)
    {
        $encodedAddress = new RTDInvoiceAddress;
        $customMapping  = Mage::getConfig()->getNode('rtd_mappings/billing_address');

        foreach ($customMapping->asArray() as $magentoKey => $rtdKey) {
            $encodedAddress->setParam($rtdKey, $billingAddress->{'get'.$magentoKey}());
        }

        $encodedOrder->setInvoiceAddress($encodedAddress);
    }

    /**
     * Encodes each order line.
     *
     * @param RTDOrder               $encodedOrder
     * @param Mage_Sales_Model_Order $magentoOrder
     *
     * @return void
     */
    protected function _encodeOrderLines(RTDOrder $encodedOrder, Mage_Sales_Model_Order $magentoOrder)
    {
        $mapping = Mage::getConfig()->getNode('rtd_mappings/order_item');

        foreach ($magentoOrder->getAllItems() as $orderItem) {

            if ( ! $orderItem->isSimple()) {
                continue;
            }

            $encodedOrder->addLine($this->_encodeOrderItem($mapping, $orderItem));
        }
    }

    /**
     * Encodes an individual order item.
     *
     * @param array                       $mapping
     * @param Mage_Sales_Model_Order_Item $item
     *
     * @return \SixBySix\RealtimeDespatch\Entity\OrderLine
     */
    protected function _encodeOrderItem($mapping, Mage_Sales_Model_Order_Item $item)
    {
        $orderFlowLine = new RTDOrderLine;

        foreach ($mapping->asArray() as $magentoKey => $rtdKey) {
            $orderFlowLine->setParam($rtdKey, $item->{'get'.$magentoKey}());
        }

        if (Mage::helper('realtimedespatch/export_order')->isDiscountEnabled()) {
            $orderFlowLine = $this->_calculateDiscounts($item, $orderFlowLine);
        }

        $orderFlowLine->setProduct(new RTDProduct($item->getSku()));

        return $orderFlowLine;
    }

    /**
     * Calculates the discounts for an item
     *
     * @param Mage_Sales_Model_Order_Item                 $item
     * @param \SixBySix\RealtimeDespatch\Entity\OrderLine $orderFlowLine
     *
     * @return \SixBySix\RealtimeDespatch\Entity\OrderLine
     */
    protected function _calculateDiscounts($item, $orderFlowLine)
    {
        $totalPriceNet   = $item->getRowTotal() + $item->getHiddenTaxAmount() + $item->getWeeeTaxAppliedRowAmount() - abs($item->getDiscountAmount());
        $totalTax        = $item->getTaxAmount() + $item->getWeeeTaxAppliedRowAmount();
        $totalPriceGross =  $item->getRowTotal() + ($item->getTaxAmount() + $item->getWeeeTaxAppliedRowAmount()) + $item->getHiddenTaxAmount() - abs($item->getDiscountAmount());
        $unitPriceNet    = ($totalPriceNet / (double)$item->getQtyOrdered());
        $unitTax         = ($totalTax / (double)$item->getQtyOrdered());
        $unitPriceGross  = ($totalPriceGross / (double)$item->getQtyOrdered());

        $orderFlowLine->setParam('totalPriceNet', $totalPriceNet);
        $orderFlowLine->setParam('totalTax', $totalTax);
        $orderFlowLine->setParam('totalPriceGross', $totalPriceGross);
        $orderFlowLine->setParam('unitPriceNet', $unitPriceNet);
        $orderFlowLine->setParam('unitTax', $unitTax);
        $orderFlowLine->setParam('unitPriceGross', $unitPriceGross);

        return $orderFlowLine;
    }

    /**
     * Encodes an order shipment.
     *
     * @param RTDOrder               $encodedOrder
     * @param Mage_Sales_Model_Order $magentoOrder
     *
     * @return void
     */
    protected function _encodeShipment(RTDOrder $encodedOrder, Mage_Sales_Model_Order $magentoOrder)
    {
        $shipment      = new RTDShipment;
        $customMapping = Mage::getConfig()->getNode('rtd_mappings/order_shipment');

        foreach ($customMapping->asArray() as $magentoKey => $rtdKey) {
            $shipment->setParam($rtdKey, $magentoOrder->{'get'.$magentoKey}());
        }

        $encodedOrder->setShipment($shipment);
    }
}
