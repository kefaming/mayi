<?php
namespace app\index\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用与此控制器同名的数据模型
use app\system\model\SystemBase as  sysbasemod;


class Index extends AdminBase
{
    // 主页
    public function index()
    {
        // 获取信息
        $sysbasemod = new sysbasemod();     # 关键字
        $list = $sysbasemod::sysInfo();     # 描述
        $list['version'] = config('shangma.version');   # 版本号
        $ad = new \app\admin\model\Admin;   # 获取用户姓名
        $list->xingming = $ad->where('id', session('user_id'))->value('xingming');
        $auth = new \app\admin\model\AuthRule;      # 菜单
        $list['menu'] = $auth::menu();

        // 模版赋值
        $this->view->assign('list',$list);
        // 渲染输出
        return $this->view->fetch();
    }


    public function welcome()
    {

        //实例化数据模型
        $sysbasemod = new sysbasemod();

        // 查询系统设置
        $list = $sysbasemod
            ->order(['id'=>'desc'])
            ->field('thinks, danwei')
            ->find();

        // 查询用户登信息
        $list['username'] = session('admin.username');
        $list['webtitle'] = config('shangma.webtitle');

        // 查询用户姓名及用户拥有的权限
        $admin = new \app\admin\model\Admin;
        $list['xingming'] = $admin->where('id',session('user_id'))
            ->value('xingming');

        // 过期时间
        $guoqi = $admin->where('id', session('user_id'))
            ->field('guoqi')
            ->find();
        $list['guoqi'] = $guoqi->guoqi;


        $list['group'] = $admin->searchOne(session('user_id'))->groupnames;

        $list['server'] = request()->server();
        // 获取系统版本号
        $list->version = config('shangma.version');
        // 获取php版本号
        $list->php = phpversion();
        // 获取数据库版本号
        $list->sql = \think\facade\Db::query("select VERSION()");
        $list->sql = $list->sql[0]['VERSION()'];

        // 获取服务器操作系统
        $list->xitong = php_uname('s');
        // 获取磁盘空间
        if ($list->xitong == 'Linux') {
            $list->kongjian = disk_free_space('.');
        } else {
            $list->kongjian = disk_free_space('/');
        }
        $list->kongjian = format_bytes($list->kongjian);
        // 获取Session过期时间
        $list->session = \think\facade\Config::get('session.expire').'s';
        // 获取thinkphp版本
        $list->thinkphp = \think\facade\App::version();

        // 考试数
        $con = new \app\kaoshi\model\Kaoshi;
        $list['kaoshi'] = $con->count();
        // 学生数
        $con = new \app\teach\model\Banji;
        $tempsrc['ruxuenian'] = array_keys(\app\facade\Tools::nianJiNameList());
        $tempsrc['auth'] = [
                'check' => false
                ,'banji_id' => array()
            ];
        $tempsrc['all'] = true;
        $bjids = $con->search($tempsrc)->column('id');
        $con = new \app\student\model\Student;
        $list['student'] =  $con->where('banji_id', 'in', $bjids)
            ->count();
        // 管理员数
        $con = new \app\admin\model\Admin;
        $list['admin'] =  $con->count();
        // 荣誉数
        $con = new \app\rongyu\model\JsRongyuInfo;
        $list['rongyu'] =  $con->count();
        // 课题数
        $con = new \app\keti\model\KetiInfo;
        $list['keti'] =  $con->count();
        // 缓存时间
        $list['cacheExpire'] = config('cache.stores.file.expire') . 's';

        $view = app('view');
        // 模版赋值
        $view->assign('list',$list);


        // 渲染输出
        return $view->fetch('welcome');
    }
}
