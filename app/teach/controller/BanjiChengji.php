<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用班级数据模型类
use app\teach\model\BanjiChengji as bjcjmod;

class BanjiChengji extends AdminBase
{
    /**
     * 班级历次成绩列表
     *
     * @return \think\Response
     */
    public function index($banji)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '班级历次考试成绩';
        $list['dataurl'] = '/teach/banjicj/data';
        $list['banji_id'] = $banji;

        $sbj = new \app\teach\model\Subject;
        $src = ['kaoshi'=>1];
        $list['subject_id'] = $sbj->search($src)->toArray();
        $list['tjxm'] = src_tjxm(12201);

        // 模板赋值
        $this->view->assign('list',$list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取班级成绩数组
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page'=>'1'
                ,'limit'=>'10'
                ,'field'=>'ks_id'
                ,'order'=>'desc'
                ,'banji_id'=>''
                ,'category_id'=>''
                ,'xueqi_id'=>''
                ,'bfdate'=>''
                ,'enddate'=>''
            ],'POST');

        // 根据条件获取班级成绩
        $bjcjmod = new \app\chengji\model\TongjiBj;
        $data = $bjcjmod->srcBanjiChengji($src);
        $bjcjmod = new bjcjmod;
        $data = $bjcjmod->banjiChengjiList($data);
        $data = reset_data($data, $src);

        return json($data);
    }


    // 获取条形统计图数据
    public function ajaxDataTiaoXing()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'field' => 'ks_id'
                ,'order' => 'desc'
                ,'banji_id' => ''
                ,'subject_id' => ''
                ,'category_id' => ''
                ,'xueqi_id' => ''
                ,'bfdate' => ''
                ,'enddate' => ''
            ],'POST');


        // 获取统计图需要的数据
        $bjcjmod = new \app\chengji\model\TongjiBj;
        $data = $bjcjmod->srcBanjiChengji($src);
        $bjcjmod = new bjcjmod;
        $data = $bjcjmod->tiaoXing($data, $src['subject_id']);

        return json($data);
    }

}
