<?php
declare (strict_types = 1);

namespace app\middleware;

use \app\login\controller\YanZheng as yz;

class Login
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $category = session('onlineCategory');

        $yz = [
            'val' => 0
            ,'msg' => '已经掉线啦~'
        ];

        switch ($category) {
            case 'admin':
                $yz = yz::admin(session('username'), session('password'));
                break;
            case 'student':
                $yz = yz::student(session('username'), session('password'));
                break;
            default:
                \app\facade\OnLine::jump('/login', '请选择用户角色');
                break;
        }

        if ($yz['val'] === 0)
        {
            \app\facade\OnLine::jump('/login', $yz['msg']);
        }


        return $next($request);
    }
}
