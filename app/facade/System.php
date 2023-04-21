<?php
namespace app\facade;

use think\Facade;

class System extends Facade
{
    protected static function getFacadeClass()
    {
    	return '\app\tools\controller\System';
    }
}
