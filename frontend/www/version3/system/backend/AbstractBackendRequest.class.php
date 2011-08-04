<?php
require_once __DIR__.'/BackendRequest.class.php';
abstract class AbstractBackendRequest extends BackendRequest
{
	public function __construct()
	{
		parent::__construct(get_class($this).'Handler');
	}
	protected /*void*/ function create()
	{
		$this->setMethod(BackendRequest_CREATE);
	}
	protected /*void*/ function read()
	{
		$this->setMethod(BackendRequest_READ);
	}
	protected /*void*/ function update()
	{
		$this->setMethod(BackendRequest_UPDATE);
	}
	protected /*void*/ function delete()
	{
		$this->setMethod(BackendRequest_DELETE);
	}
}
?>