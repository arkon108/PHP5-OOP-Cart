<?php 

/**
 * Concrete Decorator - Discount Decorator modifies the cost of contained object
 * 
 * @author Saša Tomislav Mataić - <sasa.tomislav [ AT ] mataic.com>
 */

include_once 'lib/Product/Decorator.php';

class Discount_Decorator extends Product_Decorator 
{
	protected $_discount = null;
	
	/**
	 * Set discount percent amount
	 * @param float $discount
	 */
	public function setDiscount($discount)
	{
		if ( ! is_numeric($discount)) {
			return FALSE;
		}
		
		$this->_discount = $discount;
		
		return $this;
	}
	
	/**
	 * Get string representation of discount percentage
	 * @return string
	 */
	public function getDescription()
	{
		$discountPercent = $this->_discount * 100;
		return $this->_product->getDescription() . " (discount $discountPercent%)";
	}
	
	/**
	 * Fetch "decorated" product cost
	 * (original product cost modified by the decorator)
	 * @return float discount price 
	 */
	public function getCost()
	{
		if (NULL === $this->_discount) {
			return $this->_product->getCost();
		}
		
		return number_format($this->_product->getCost() * $this->_discount, 2);
	}
}

