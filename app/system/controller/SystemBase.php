<?php

namespace app\system\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用与此控制器同名的数据模型
use app\system\model\SystemBase as  sysbasemod;


class SystemBase extends AdminBase
{
    // 系统设置
    public function edit()
    {
        // 获取用户信息
        $sys = new sysbasemod;
        $list['data'] = $sys
            ->sysInfo();

        $list['set'] = array(
            'webtitle' => '设置系统信息'
            ,'butname' => '设置'
            ,'formpost' => 'PUT'
            ,'url' => 'update/' . $list['data']['id']
        );

        // 模板赋值
        $this->view->assign('list', $list);


        // 渲染模板
        return $this->view->fetch('edit');
    }


    // 更新资源
    public function update($id)
    {

        // 获取表单数据
        $list = request()->only([
            'keywords'
            ,'description'
            ,'thinks'
            ,'danwei'
            ,'gradelist'
            ,'classmax'
            ,'classalias'
            ,'xuenian'
            ,'studefen'
            ,'sys_title'
        ], 'put');
        $list['id'] = $id;
        $findy = strpos($list['xuenian'], '月');
        $yue = substr($list['xuenian'],0,$findy);
        $findy = $findy + 3;
        $findr = strpos($list['xuenian'], '日');
        $ri = substr($list['xuenian'], $findy, $findr - $findy);
        $list['xuenian'] = date('Y') . '-' . $yue . '-' . $ri;

        // 验证表单数据
        $validate = new \app\system\validate\SystemBase;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg'=>$msg,'val'=>0]);;
        }

        $sys = new sysbasemod;
        $sysList = $sys->find($id);
        $sysList->keywords = $list['keywords'];
        $sysList->description = $list['description'];
        $sysList->thinks = $list['thinks'];
        $sysList->danwei = $list['danwei'];
        $sysList->gradelist = $list['gradelist'];
        $sysList->classmax = $list['classmax'];
        $sysList->classalias = $list['classalias'];
        $sysList->xuenian = $list['xuenian'];
        $sysList->studefen = $list['studefen'];
        $sysList->sys_title = $list['sys_title'];
        $data = $sysList->save();

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '设置成功','val'=>1]
            : $data = ['msg' => '数据处理错误','val' => 0];

        // 返回信息
        return json($data);
    }


    // 系统初始化
    public function resetMayi()
    {
        $data = ['msg' => '初始化成功', 'val' => 1];
        try {
            // 删除管理员
            \think\facade\Db::query("Delete From cj_admin where id>2");

            // 删除用户组
            \think\facade\Db::query("Delete From cj_auth_group where id>1");


            // 删除用户与用户组对应
            \think\facade\Db::query("Delete From cj_auth_group_access where id>0");

            // 删除班主任
            \think\facade\Db::query("Delete From cj_ban_zhu_ren where id>0");

            // 删除班级
            \think\facade\Db::query("Delete From cj_banji where id>0");

            // 删除教研组
            \think\facade\Db::query("Delete From cj_jiaoyanzu where id>0");

            // 删除教研组长
            \think\facade\Db::query("Delete From cj_jiaoyan_zuzhang where id>0");

            // 删除成绩
            \think\facade\Db::query("Delete From cj_chengji where id>0");

            // 删除单位荣誉
            \think\facade\Db::query("Delete From cj_dw_rongyu where id>0");

            // 删除单位荣誉参与
            \think\facade\Db::query("Delete From cj_dw_rongyu_canyu where id>0");

            // // 删除分工
            // $obj = new \app\rongyu\model\FenGong;
            // $list = $obj::destroy(function ($query) {
            //     $query->where('id', '>', 0);
            // });
            //
            // 删除文件
            \think\facade\Db::query("Delete From cj_fields where id>0");
 
            // 删除教师荣誉
            \think\facade\Db::query("Delete From cj_js_rongyu where id>0");

            // 删除教师荣誉参与
            \think\facade\Db::query("Delete From cj_js_rongyu_canyu where id>0");

            // 删除教师荣誉信息
            \think\facade\Db::query("Delete From cj_js_rongyu_info where id>0");

            // 删除考号
            \think\facade\Db::query("Delete From cj_kaohao where id>0");

            // 删除考试
            \think\facade\Db::query("Delete From cj_kaoshi where id>0");

            // 删除单位荣誉参与
            \think\facade\Db::query("Delete From cj_kaoshi_set where id>0");

            // 删除课程表
          //
            // 删除课程表临时表
            // $obj = new \app\rongyu\model\DwRongyuCanyu;
            // $list = $obj::destroy(function ($query) {
            //     $query->where('id', '>', 0);
            // });
            //
            // 删除课节
            // $obj = new \app\rongyu\model\DwRongyuCanyu;
            // $list = $obj::destroy(function ($query) {
            //     $query->where('id', '>', 0);
            // });            // $obj = new \app\rongyu\model\DwRongyuCanyu;
            // $list = $obj::destroy(function ($query) {
            //     $query->where('id', '>', 0);
            // });
  
            //
            // 删除课题删
            \think\facade\Db::query("Delete From cj_lixiang where id>0");

            // 删除课题删
            \think\facade\Db::query("Delete From cj_jieti where id>0");

            // 删除课题参与
            \think\facade\Db::query("Delete From cj_keti_canyu where id>0");

            // 删除课题信息
            \think\facade\Db::query("Delete From cj_keti_info where id>0");

            // 删除单位
            \think\facade\Db::query("Delete From cj_school where id>0");

            // 删除学生信息
            \think\facade\Db::query("Delete From cj_student where id>0");

            // 删除班级成绩统计
            \think\facade\Db::query("Delete From cj_tongji_bj where id>0");

            // 删除统计记录
            \think\facade\Db::query("Delete From cj_tongji_log where id>0");

            // 删除年级成绩统计
            \think\facade\Db::query("Delete From cj_tongji_nj where id>0");

            // 删除学校成绩统计
            \think\facade\Db::query("Delete From cj_tongji_sch where id>0");

            // 删除学期
            \think\facade\Db::query("Delete From cj_xueqi where id>1");

        } catch (ValidateException $e) {
            $data = ['msg' => '初始化失败', 'val' => 0];
        }

        // 返回信息
        return json($data);
    }

}
