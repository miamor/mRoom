<?php // Cat.php
abstract class Page extends Config implements render
{
	protected $render;
	protected $HOST = "localhost";
	protected $USERNAME = "root" ;
	protected $PASSWORD = "";
	protected $DATABASE = "mcode";

	public function setPage (render $page)
	{
		$this->render = $page;
	}

	public function __construct () 
	{
		return parent::connect();
	}

	// Destructor - close DB connection
	public function __destruct() 
	{
		return parent::disconnect();
	}

	public function index ()
	{
		return $this->render->index();
	}

	public function lists ()
	{
		return $this->render->lists();
	}

	public function view ()
	{
		return $this->render->view();
	}

}
