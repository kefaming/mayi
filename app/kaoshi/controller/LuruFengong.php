<?php

namespace app\kaoshi\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

// 引用考号数据模型类
use app\kaoshi\model\LuruFengong as lrfg;
// 引用考号数据模型类
use app\kaoshi\model\KaoshiSet as ksset;


class LuruFengong extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($kaoshi_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '录入分工';
        $list['dataurl'] = '/kaoshi/lrfg/data';
        $list['kaoshi_id'] = $kaoshi_id;

        $ksset = new ksset;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        $list['subject'] = $ksset->srcSubject($src);
        $nj = $ksset->srcGrade($src);
        $list['nj'] = $nj;
        $kaoshi = new \app\kaoshi\model\Kaoshi;
        $list['sj']  = $kaoshi::where('id', $kaoshi_id)->value('bfdate');

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }

    // 获取考试信息列表
    public function ajaxData()
    {

        // 获取参数
        $src = $this->request
            ->only([
                'kaoshi_id' => 0
                ,'banji_id' => array()
                ,'subject_id' => array()
                ,'ruxuenian' => ''
                ,'page' => '1'
                ,'limit' => '10'
                ,'field' => 'update_time'
                ,'order' => 'asc'
                ,'searchval' => ''
            ], 'POST');

        // 根据条件查询数据
        $lrfgsbj = new lrfg;
        $data = $lrfgsbj->search($src);
        $src['all'] = true;
        $cnt = $lrfgsbj->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($kaoshi_id)
    {

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '录入成绩分工'
            ,'butname' => '分工'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/lrfg/save'
            ,'kaoshi_id' => $kaoshi_id
        );

                // 获取考试时间
        $ks = new \app\kaoshi\model\Kaoshi;
        $enddate = $ks->kaoshiInfo($kaoshi_id);
        $list['set']['enddate'] = $enddate->getData('enddate');

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {
        // 获取参数
        $src = $this->request
                ->only([
                    'kaoshi_id'
                    ,'admin_id' => array()
                    ,'subject_id' => array()
                    ,'banji_id' => array()
                ], 'POST');

        event('kslu', $src['kaoshi_id']);

        // 验证表单数据
        $validate = new \app\kaoshi\validate\LuruFengong;
        $result = $validate->scene('create')->check($src);
        $msg = $validate->getError();
        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg,'val' => 0]);;
        }

        // 用户修改分工权限验证
        if(session('user_id') != 1 && session('user_id') != 2) {
            $ks = new \app\kaoshi\model\Kaoshi;
            $user_id = $ks->where('id', $src['kaoshi_id'])->value('user_id');
            if (session('user_id') != $user_id) {
                $data = ['msg' => '考试创建者和超级管理员有权限设置。', 'val' => 0];
                return json($data);
            }
        }
        

        // 整理参数
        $list = array();
        $src['admin_id'] = explode(',', $src['admin_id']);
        foreach ($src['admin_id'] as $admin_k => $admin_v) {
            foreach ($src["banji_id"] as $banji_k => $banji_v) {
                foreach ($src["subject_id"] as $sbj_k => $sbj_v) {
                    $list[] = [
                        'kaoshi_id' => $src['kaoshi_id']
                        ,'banji_id' => $banji_v
                        ,'subject_id' => $sbj_v
                        ,'admin_id' => $admin_v
                    ];
                }
            }
        }
        
        // 查询已经存在数据删除并添加新数据
        $lrfg = new lrfg;
        
        $data = $lrfg->saveAll($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    // 删除考号
    public function delete()
    {

        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $fg_id = $id[0];

        // 用户修改分工权限验证
        if(session('user_id') != 1 && session('user_id') != 2) {
            $ks = new \app\kaoshi\model\Kaoshi;
            $user_id = $ks->where('id', $fg_id)->value('user_id');
            if (session('user_id') != $user_id) {
                $data = ['msg' => '考试创建者和超级管理员有权限设置。', 'val' => 0];
                return json($data);
            }
        }

        // 判断考试结束时间是否已过
        $lrfg = new lrfg;
        $ksid = $lrfg::where('id', $id[0])->value('kaoshi_id');
        event('kslu', $ksid);

        $data = lrfg::destroy($id, true);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功','val' => 1]
            : $data = ['msg' => '数据处理错误','val' => 0];
        // 返回信息
        return json($data);
    }


    // 获取录入分工班级
    public function class()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'school_id' => ''
                ,'ruxuenian' => ''
                ,'kaoshi_id' => ''
                ,'limit' => 100
                ,'all' => true
            ], 'POST');
        $lrfg = new \app\kaoshi\model\LuruFengong;
        $bj = $lrfg->srcMyLuruBanji($src);
        $bj = reset_data($bj, $src);
        return json($bj);
    }


    // 获取录入分工班级
    public function subject()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'school_id' => ''
                ,'ruxuenian' => ''
                ,'kaoshi_id' => ''
                ,'limit' => 100
            ], 'POST');
        $lrfg = new \app\kaoshi\model\LuruFengong;
        $bj = $lrfg->srcMyLuruSubject($src);
        $bj = reset_data($bj, $src);
        return json($bj);
    }
}
