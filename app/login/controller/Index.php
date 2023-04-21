<?php
namespace app\login\controller;

// 引用控制器基类
use app\BaseController;

use app\login\controller\YanZheng as yz;


class Index extends BaseController
{
    // 显示登录选择界面
    public function index()
    {
        // 清除cookie
        cookie('userid', null);
        cookie('username', null);
        cookie('password', null);
        // 清除session（当前作用域）
        session(null);

        // 启动事务
        \think\facade\Db::startTrans();
        try {
            $sysbasemod = new \app\system\model\SystemBase;     # 关键字
            $list = $sysbasemod::sysInfo();
            if($list == null) {
                $list['sys_title'] = config('shangma.webtitle');
            }
            // \think\facade\Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            $list['sys_title'] = config('shangma.webtitle');
        }
        // 获取系统名称和版本号
        $list['version'] = config('shangma.version');
        $this->view->assign('list',$list);

        // 渲染输出
        return $this->view->fetch('index');
    }


    // 管理员登录验证
    public function admin()
    {
        // 获取表单数据
        $list = request()
            ->only([
                'username'
                ,'password'
                ,'category'
            ]);
        $list['username'] = trim($list['username']);
        $list['password'] = trim($list['password']);

        $validate = new \app\login\validate\Yanzheng;
        $result = $validate->scene('admin')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        $yz = yz::admin($list['username'], $list['password']);

        if($yz['val'] === 1)
        {
            // 获取服务器密码
            $admin = new \app\admin\model\Admin;
            $userinfo = $admin::where('username|phone', $list['username'])
                ->where('status', 1)
                ->field('id, lastip, username, ip, denglucishu, lasttime, thistime, password, guoqi')
                ->find();
            $now = time();
            if ($userinfo->getData("guoqi") < $now && $list['password'] == '123456') {
                $userinfo->status = false;
            }
            // 将本次信息上传到服务器上
            $userinfo->lastip = $userinfo->ip;
            $userinfo->ip = request()->ip();
            $userinfo->denglucishu = ['inc', 1];
            $userinfo->lasttime = $userinfo->getData('thistime');
            $userinfo->thistime = time();
            $userinfo->save();
        }

        return json($yz);
    }


    // 学生登录验证
    public function student()
    {
        // 获取表单数据
        $list = request()
            ->only([
                'username'
                ,'password'
                ,'category'
            ]);
        $list['username'] = trim($list['username']);
        $list['password'] = trim($list['password']);

        $validate = new \app\login\validate\Yanzheng;
        $result = $validate->scene('admin')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        $yz = yz::student($list['username'], $list['password']);

        if($yz['val'] === 1)
        {
            // 获取服务器密码
            $stu = new \app\student\model\Student;
            $userinfo = $stu::where('shenfenzhenghao', $list['username'])
                ->where('status', 1)
                ->field('id, lastip, shenfenzhenghao, ip, denglucishu, lasttime, thistime, password')
                ->find();
            // 将本次信息上传到服务器上
            $userinfo->lastip = $userinfo->ip;
            $userinfo->ip = request()->ip();
            $userinfo->denglucishu = ['inc', 1];
            $userinfo->lasttime = $userinfo->getData('thistime');
            $userinfo->thistime = time();
            $userinfo->save();
        }

        return json($yz);
    }


    // 系统更新日志
    public function shangmaLog()
    {
        // 渲染模板
        return $this->view->fetch();
    }


    // 错误页面
    public function myerror()
    {
        // 获取系统名称和版本号
        $list['webtitle'] = config('shangma.webtitle');
        $list['version'] = config('shangma.version');

        $this->view->assign('list',$list);

        // 渲染输出
        return $this->view->fetch();
    }


    // 系统维护
    public function weihu()
    {
        // 获取系统名称和版本号
        $list = [
            'shijian' => config('shangma.shijian')
            ,'shichang' => config('shangma.shichang')
            ,'webtitle' => '系统维护中'
            ,'version' => config('shangma.version')
        ];

        $this->view->assign('list',$list);
        return $this->view->fetch();
    }

}
