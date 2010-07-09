<?php 
/**
 * Concrete Persistance object
 * Session persistance uses namespaces in $_SESSION global array
 *  
 * @author Saša Tomislav Mataić - <sasa.tomislav [ AT ] mataic.com>
 */

include 'lib/Persistance/Interface.php';

class Persistance_Session implements Persistance_Interface
{
	/**
	 * Default namespace
	 * @var string
	 */
	protected $_namespaceDefault = 'default_persistance';
	
	/**
	 * Namespace used in $_SESSION global array
	 * @var int
	 */
	protected $_namespaceId = NULL;
	
	/**
	 * Items saved to persistance
	 * @var array
	 */
	protected $_contents = array();
	
	/**
	 * Constructor
	 * @param string $namespace
	 */
	public function __construct($namespace = NULL)
	{
		session_start();
		
		if (NULL !== $namespace) {
			$this->_namespaceId = $namespace;
		} else {
			$this->_namespaceId = $this->_namespaceDefault;
		}
		
		if ( ! isset($_SESSION[$this->_namespaceId])) {
			$_SESSION[$this->_namespaceId] = array();
		}
	}
	
	/**
	 * Set id as namespace
	 * @param $id
	 * @return Persistance_Session provides fluent interface
	 */
	public function setId($id = NULL) 
	{
		if (NULL === $id) {
			throw new Exception('id cannot be NULL');
		}
		
		$this->_namespaceId = $id;
		
		return $this;
	}
	
	/**
	 * Fetch namespace id
	 * @return mixed
	 */
	public function getId()
	{
		return $this->_namespaceId;
	}
	
	/**
	 * Fetch contents from persistance layer to member variable
	 * @param mixed $id
	 * @return Persistance_Session provides fluent interface
	 */
	public function load($id = NULL)
	{
		if ( NULL !== $id) {
			$this->setId($id);
		}
		
		$this->_contents = $_SESSION[$this->_namespaceId];
		
		return $this;
	}
	
	/**
	 * Save contents to persistance layer
	 * @param mixed $id
	 */
	public function save($id = NULL)
	{
		if ( NULL !== $id) {
			$this->setId($id);
		}
		
		$_SESSION[$this->_namespaceId] = $this->_contents;
	}
	
	/**
	 * Fetch items stored in persistance object
	 * @return array contents
	 */
	public function getContents()
	{
		return $this->_contents;
	}
	
	/**
	 * Import items
	 * If trying to set empty contents, they will be deleted
	 * @param array $contents
	 * @return Persistance_Session provides fluent interface
	 */
	public function setContents($contents = NULL)
	{
		if (NULL === $contents || empty($contents)) {
			$this->emptyContents();
		}
		$this->_contents = $contents;
		return $this;
	}
	
	/**
	 * Delete all contents
	 * @return Perstistance_Session provides fluent interface
	 */
	public function emptyContents()
	{
		$this->_contents = array();
		return $this;
	}
	
}