<?php 

/**
 * Decorator pattern is implemented with this Abstract Product decodator
 * The Product_Decorator extends the Product class which enables 
 * the Products can be wrapped in Decorators
 * This pattern is an example of "favor composition over inheritance" principle
 * 
 * Notice how product methods are forwarded to the contained product object
 * @author Saša Tomislav Mataić - <sasa.tomislav [ AT ] mataic.com>
 */

include_once 'lib/Product/Abstract.php';

abstract class Product_Decorator extends Product_Abstract 
{

	/**
	 * Wrapped product
	 * @var Product_Abstract instance
	 */
    protected $_product = null;
    
    /**
     * Constructor
     * @param $product
     */
    public function __construct(Product_Interface $product)
    {
        $this->_product = $product;
    }
    
    /**
     * Return product description
     * @return string
     */
    public function getDescription()
    {
    	return $this->_product->getDescription();
    }
    
    /**
     * Fetch product attribute
     * @param mixed $key
     */
    public function __get($key)
    {
        return $this->_product->__get($key);
    }
    
    /**
     * Set product interface
     * @param $key
     * @param $value
     */
    public function __set($key = NULL, $value = NULL)
    {
        return $this->_product->__set($key, $value);
    }
    
    /**
     * Check if product attribute is set
     * @param $key
     */
    public function __isset($key)
    {
        return $this->_product->__isset($key);
    }
	
}
