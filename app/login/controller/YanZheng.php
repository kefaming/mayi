<?php
namespace app\login\controller;


class YanZheng
{
    // 获学生帐号密码信息
    public static function admin($username, $password)
    {
        // 获取服务器密码
        $admin = new \app\admin\model\Admin;
        $userinfo = $admin::where('username|phone', $username)
            ->where('status', 1)
            ->field('id, lastip, username, ip, denglucishu, lasttime, thistime, password, phone')
            ->find();
        if ($userinfo == null)
        {
            // 验证结果;
            $data = ['msg' => '教师帐号不存在或被禁用', 'val' => 0];
            return $data;
        }

        // 验证用户名和密码
        $check = login_check($password, $userinfo->password);

        if ($check === true)
        {
            session('onlineCategory', 'admin');
            session('user_id', $userinfo->id);
            session('username', $username);
            session('password', $password);
            // 跳转到首页
            $data = ['msg' => '验证成功', 'val' => 1, 'url' =>'/'];
        } else {
            // 提示错误信息
            $data = ['msg' => '用户名或密码错误', 'val' => 0];
        }

        return $data;
    }


    // 获学生帐号密码信息
    public static function student($username, $password)
    {
        // 获取服务器密码
        $stu = new \app\student\model\Student;
        $userinfo = $stu::where('shenfenzhenghao', $username)
            ->where('status', 1)
            ->field('id, lastip, shenfenzhenghao, ip, denglucishu, lasttime, thistime, password')
            ->find();
        if ($userinfo == null)
        {
            // 验证结果;
            $data = ['msg' => '学生帐号不存在或被禁用', 'val' => 0];
            return $data;
        }

        // 验证用户名和密码
        $check = login_check($password, $userinfo->password);

        if ($check === true)
        {
            session('onlineCategory', 'student');
            session('user_id', $userinfo->id);
            session('username', $username);
            session('password', $password);
            // 跳转到首页
            $data = ['msg' => '验证成功', 'val' => 1, 'url' =>'\\studentSearchChengji\\index\\index'];
        } else {
            // 提示错误信息
            $data = ['msg' => '用户名或密码错误', 'val' => 0];
        }

        return $data;
    }

}
