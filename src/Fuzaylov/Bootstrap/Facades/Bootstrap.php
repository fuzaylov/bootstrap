<?php namespace Fuzaylov\Bootstrap\Facades;

use Illuminate\Support\Facades\Facade;

class Bootstrap extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'bootstrap';
	}

} 