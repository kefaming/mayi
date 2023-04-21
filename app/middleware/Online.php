<?php
declare (strict_types = 1);

namespace app\middleware;

class Online
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
        $weihu = config('shangma.weihu');
        // 判断是否在线，如果不在线则跳转
        $online = session('?onlineCategory');
        if($online !=true or strlen(session('onlineCategory')) < 1)
        {
            \app\facade\OnLine::jump('/login', '登录超时啦~');
        }

        $user_id = session('user_id');
        if($weihu == true && $user_id > 2)
        {
            \app\facade\OnLine::jump('/login/weihu', '系统维护中~');
            session(null);
        }

        return $next($request);
    }
}
