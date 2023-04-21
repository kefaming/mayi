<?php

namespace app\kaoshi\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

// 引用考试数据模型类
use app\kaoshi\model\Kaoshi as KS;
use app\middleware\KaoshiStatus;


class Index extends AdminBase
{
    // 显示考试列表
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '考试列表';
        $list['dataurl'] = '/kaoshi/index/data';
        $list['status'] = '/kaoshi/index/status';
        $list['luru'] = '/kaoshi/index/luru';

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
                'xueqi_id' => ''
                ,'category_id' => ''
                ,'page' => '1'
                ,'limit' => '10'
                ,'field' => 'id'
                ,'order' => 'desc'
                ,'searchval' => ''
            ], 'POST');
        $ks = new KS;
        if(session('user_id') != 1 && session('user_id') !=2) {
            $src['id'] = $ks->srcAuth(); 
        }
        // 根据条件查询数据
        $data = $ks->search($src)
            ->visible([
                'id'
                ,'title'
                ,'bfdate'
                ,'enddate'
                ,'status'
                ,'luru'
                ,'ksCategory' => ['id', 'title']
                ,'ksZuzhi' => ['id', 'title']
                ,'ksXueqi' => ['id', 'title']
                ,'ksFanwei' => ['id', 'title']
                ,'update_time'
            ]);
        $src['all'] = true;
        $cnt = $ks->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 创建考试
    public function create()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '新建考试'
            ,'butname' => '创建'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/index/save'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 创建考试向导
    public function createSetp1()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '创建考试向导'
            ,'butname' => '下一步'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/index/save'
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 创建考试向导
    public function createSetp2($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '设置考试'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/kaoshiset/save'
            ,'kaoshi_id' => $kaoshi_id
        );

                // 获取考试时间
        $ks = new \app\kaoshi\model\Kaoshi;
        $enddate = $ks->kaoshiInfo($kaoshi_id);
        $list['enddate'] = $enddate->getData('enddate');
        // $list['set']['nianji'] = \app\facade\Tools::nianJiNameList('str', $enddate);

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    } 


    // 修改考试信息
    public function editSetp1($id)
    {
        // 获取考试信息
        $list['data'] = KS::where('id', $id)
            ->field('id, title, xueqi_id, category_id, bfdate, enddate, zuzhi_id, fanwei_id')
            ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑考试'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/kaoshi/index/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create_setp1');
    }


    // 统计成绩
    public function tongji($kaoshi_id)
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '统计成绩'
            ,'butname' => '一键统计'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/index/save'
            ,'kaoshi_id' => $kaoshi_id
        );

        $list['tjxm'] = [
            [
                'title' => '学生成绩在班级位置'
                ,'url' => '/chengji/bjtj/bjorder'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '学生成绩在学校位置'
                ,'url' => '/chengji/njtj/njorder'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '学生成绩在全区位置'
                ,'url' => '/chengji/schtj/schorder'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '班级成绩'
                ,'url' => '/chengji/bjtj/tongji'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '各学校各年级成绩'
                ,'url' => '/chengji/njtj/tongji'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '统计区各年级成绩'
                ,'url' => '/chengji/schtj/tongji'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '统计标准分'
                ,'url' => '/chengji/tongji/biaozhunfen'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '重算得分率'
                ,'url' => '/chengji/tongji/editdfl'
                ,'checked' => ''
            ]
        ];

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 生成考号
    public function createSetp3($kaoshi_id)
    {
        // 获取参考年级
        $ksset = new \app\kaoshi\model\KaoshiSet;
        // $list['data']['nianji'] = $ksset->srcGrade($kaoshi_id);
        // $list['data']['nianjiNum'] = array_column($list['data']['nianji'], 'ruxuenian');

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '生成考号'
            ,'butname' => '生成'
            ,'formpost' => 'POST'
            ,'url' => '/kaohao/index/saveall'
            ,'kaoshi_id' => $kaoshi_id
        );



        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 生成考号
    public function createSetp4($kaoshi_id)
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
        $list['enddate'] = $enddate->getData('enddate');

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();

    }


    // 保存信息
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'title'
            ,'xueqi_id'
            ,'category_id'
            ,'fanwei_id'
            ,'bfdate'
            ,'enddate'
            ,'zuzhi_id'
        ], 'post');

        // 验证表单数据
        $validate = new \app\kaoshi\validate\Kaoshi;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }
        $list['user_id'] = session('user_id');
        $list['user_group'] = 'admin';
        // $list['jibie_id'] = 1;

        // 保存数据
        $ks = new KS();
        $ksdata = $ks->create($list);
        $ksdata ? $data = ['msg' => '添加成功', 'val' => 1, 'kaoshi_id'=> $ksdata->id]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改考试信息
    public function edit($id)
    {
        // 获取考试信息
        $list['data'] = KS::where('id', $id)
            ->field('id, title, xueqi_id, category_id, bfdate, enddate, zuzhi_id, fanwei_id')
            ->append(['nianjiids', 'manfenedit'])
            ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑考试'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/kaoshi/index/update/' . $id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    // 更新考试信息
    public function update($id)
    {
        event('ksedit', $id);
        event('kslu', $id);
        // 获取表单数据
        $list = request()->only([
            'title',
            'xueqi_id',
            'category_id',
            'bfdate',
            'enddate',
            'zuzhi_id',
            'fanwei_id'
        ], 'post');
        $list['id'] = $id;

        // 验证表单数据
        $validate = new \app\kaoshi\validate\Kaoshi;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $ks = new KS();
        $ksdata = $ks::update($list);
        $ksdata ? $data = ['msg' => '更新成功', 'val' => 1, 'kaoshi_id'=> $ksdata->id]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置成绩是否允许操作
    public function luru()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 获取考试信息
        $data = KS::where('id', $id)->cache('kaoshiinfo')->update(['luru' => $value]);
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 删除考试
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = KS::destroy($id);
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 设置考试状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        $ks = new KS();
        $ksInfo = $ks->where('id', $id)->field('id, luru, status')->find();
        if($value == 0){
            $ksInfo->status = $value;
            $ksInfo->luru = $value;
        }else{
            $ksInfo->status = $value;
        }

        $data = $ksInfo->save();
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 查询考试成绩
    public function chengji($kaoshi_id)
    {
        // 获取考试信息
        $kaoshi = KS::where('id', $kaoshi_id)
            ->field('id, title, bfdate, enddate')
            ->find();

        // 设置页面标题
        $list['webtitle'] = $kaoshi->title  . '（' .  $kaoshi->bfdate . '~' . $kaoshi->enddate . '）';
        $list['kaoshi_id'] = $kaoshi->id;
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 获取允许录入成绩的考试
    public function srcEditKaoshi()
    {
        $src = [
            'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'luru' => 1
            ,'status' => 1
        ];
        $ks = new KS();
        // 获取考试
        $data = $ks->search($src)
            ->visible([
                'id'
                ,'title'
            ]);
        $src['all'] = true;
        $cnt = $ks->search($src)->count();
        $data = reset_data($data, $cnt);
        return json($data);
    }
}
