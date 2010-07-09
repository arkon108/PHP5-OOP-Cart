<?php 

/**
 * Common interface for persistance objects
 * Persistance objects can use session, filesystem, database, etc.
 * 
 * @author Saša Tomislav Mataić - <sasa.tomislav [ AT ] mataic.com>
 *
 */
interface Persistance_Interface
{
	public function load($id = NULL);
	
	public function save($id = NULL);
	
	public function setId($id = NULL);
	
	public function getId();
	
	public function getContents();
	
	public function setContents($contents = NULL);
	
}