<?php 

/**
 * Shopping cart example implementation
 * The Cart is implemented as Singleton
 * Cart features:
 *  - adding and removing items
 *  - dependancy injection of persistance layer
 *  - tax calculation for sum of items
 *
 * The currency is not implemented, all prices are strictly numerical
 * 
 * @author Saša Tomislav Mataić - <sasa.tomislav [ AT ] mataic.com>
 *
 */
class Cart 
{
	/**
	 * Singleton instance
	 *
	 * @var Cart
	 */
	protected static $_instance = NULL;
	
	/**
	 * Cart id
	 *
	 * @var int
	 */
	protected $_id = NULL;
	
	/**
	 * Array of cart items
	 *
	 * @var array
	 */
	protected $_items = array();
	
	/**
	 * Array of taxes
	 *
	 * @var array
	 */
	protected $_tax = array(); 
	
	/**
	 * Persistance object
	 *
	 * @var Persistance_Interface
	 */
	protected $_persistance = null;
	
	/**
	 * Private constructor, Cart implements Singleton design pattern
	 */
	private function __construct() {}
	
	/**
	 * Fetch Cart instance
	 *
	 * @return Cart
	 */
    public function getInstance()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }
	
	/**
	 * Add tax
	 *
	 * @param string $key tax code (eg. VAT)
	 * @param float $amount
	 * @return Cart provides fluent interface
	 */
	public function addTax($key = NULL, $amount = NULL)
	{
		
		if (NULL === $key) {
            throw new Exception('Tax key cannot be null');
        }
		
	    if (NULL === $amount) {
            throw new Exception('Tax amount cannot be null');
        }
		
		$this->_tax[$key] = $amount;

		return $this->_save();
	}
	
	/**
	 * Add Product item to Cart
	 *
	 * @param Product_Interface $product
	 * @return Cart provides fluent interface
	 */
	public function add(Product_Interface $product)
	{
		if (isset($this->_items[$product->id])) {
			$this->_items[$product->id]['amount']++;
		} else {
			$this->_items[$product->id]['item'] = $product;
			$this->_items[$product->id]['amount'] = 1;
		}
		
		return $this->_save();
	}
	
	/**
	 * Remove Product from cart
	 *
	 * @param int $productId
	 * @return Cart provides fluent interface
	 */
	public function remove($productId = NULL)
	{
		if (NULL === $productId) {
			return FALSE;
		}

		if (isset($this->_items[$productId])) {
			// if amount of products is 1, remove product, otherwise decrease amount
			if (1 == $this->_items[$productId]['amount']) {
				unset($this->_items[$productId]);
			} else {
				$this->_items[$productId]['amount']--;
			}
		}
		
		return $this->_save();
	}
	
	/**
	 * Empty Cart
	 *
	 * @return Cart provides fluent interface
	 */
	public function removeAll()
	{
		$this->_items = array();
		
		return $this->_save();
	}
	
	
	/**
	 * Fetch all Cart items
	 *
	 * @return array $items
	 */
	public function getAll()
	{
		return $this->_items;
	}
	
	/**
	 * Calculate total sum of all items
	 *
	 * @return int $total
	 */
	protected function _getTotal()
	{
		$total = 0;
		
		foreach ($this->_items as $item) {
            $total += $item['item']->getCost() * $item['amount'];	
		}
		
		return $total;
	}
	
	/**
	 * Calculate total sum of all items including tax
	 *
	 * @return float $totalWithTax
	 */
	public function getTotal()
	{
		$total = $this->_getTotal();
		
		if ( ! empty($this->_tax)) {
			foreach ($this->_tax as $tax) {
				$total += $total * $tax;
			}	
		}
		
		return number_format($total, 2);
	}
	
	/**
	 * Save current state to persistance object
	 * Method is used internally, the Cart doesn't need to be 
	 * explicitly saved
	 *
	 * @return Cart provides fluent interface
	 */
	protected function _save()
	{
		$contents['tax'] = $this->_tax;
		$contents['items'] = $this->_items;
		
		$this->_persistance->setContents($contents);
		$this->_persistance->save();
		
		return $this;
	}

	/**
	 * Load Cart contents from persistence object
	 *
	 * @return Cart provides fluent interface
	 */
	protected function _load()
	{
		$contents = $this->_persistance->load()->getContents();
		
		$this->_tax = $contents['tax'];
		$this->_items = $content['items'];
		
		return $this;
	}

	/**
	 * Set persistance object
	 *
	 * @param Persistance_Interface $persistance
	 * @return Cart provides fluent interface
	 */
	public function setPersistance(Persistance_Interface $persistance)
	{
		$this->_persistance = $persistance;
		
		// set Cart id in persitance
		if (NULL !== $this->_id) {
			$this->_persistance->setId($this->id);
		}
		
		// load from persistance
		$contents = $this->_persistance->load()->getContents();
		
		$this->_tax = $contents['tax'];
		$this->_items = $contents['items'];
		
		return $this; 
	}
	
	/**
	 * Set Cart id
	 * @param int $id
	 * @return Cart provides fluent interface
	 */
	public function setId($id = NULL) 
	{
		if (NULL === $id) {
			throw new Exception('Cannot set NULL id');
		}
		
		$this->_id = $id;
		
		// set the same id to persistance
		$this->_persistance->setId($this->_id);
		
		return $this;
	}
	
	/**
	 * Fetch id
	 * @return int Cart id
	 */
	public function getId()
	{
		return $this->_id;
	}
}

