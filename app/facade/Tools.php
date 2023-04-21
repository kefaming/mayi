<?php
namespace app\facade;

use think\Facade;

class Tools extends Facade
{
    protected static function getFacadeClass()
    {
    	return '\app\tools\controller\Index';
    }
}
