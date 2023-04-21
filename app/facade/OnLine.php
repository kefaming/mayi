<?php
namespace app\facade;

use think\Facade;

class OnLine extends Facade
{
    protected static function getFacadeClass()
    {
    	return '\app\tools\controller\OnLine';
    }
}
