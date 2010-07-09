<?php 

/**
 * Any product implementing the Product_Interface can be handled by Cart
 * In that way, the Cart is behaving to the Products by their interface,
 * which is an example of "code to the interface, not to the implementation" principle
 * 
 * @author Saša Tomislav Mataić - <sasa.tomislav [ AT ] mataic.com>
 */
interface Product_Interface
{
	public function getCost();
	
	public function setCost($cost = NULL);
	
	public function getDescription();
	
	public function setDescription($description = NULL);
}
