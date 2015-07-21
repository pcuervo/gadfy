<?php

class Ikantam_Buyback_Block_Order_Totals extends Mage_Sales_Block_Order_Totals
{





    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        $source = $this->getSource();

        $this->_totals = array();

        $this->_totals['grand_total'] = new Varien_Object(array(
            'code'  => 'grand_total',
            'field'  => 'grand_total',
            'strong'=> true,
            'value' => $source->getGrandTotal(),
            'label' => $this->__('Grand Total')
        ));


        return $this;
    }


}
