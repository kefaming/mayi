<?php
namespace app;

// 引用控制器基类
use app\BaseController;

/**
 * 控制器基础类
 */
abstract class ToolsBase  extends BaseController 
{
    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [
        'online'
    ];
}
