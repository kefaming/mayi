<?php

namespace app\rongyu\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用荣誉信息数据模型
use app\rongyu\model\Tongji as tj;

class Tongji extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '荣誉统计';
        $list['dataurl'] = '/rongyu/tongji/data';
        $list['status'] = '/rongyu/tongji/status';
        $list['luru'] = '/rongyu/tongji/luru';

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染模板
        return $this->view->fetch();
    }


    // 获取区各级立项课题数
    public function quGeji(){
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => array()
                ,'betweentime'
                ,'category_ryc'
            ], 'POST');
        // 获取课题列表
        $tj = new tj();
        $data = $tj->quGeji($src);
        return json($data);
    }


    // 获取区各单位立项课题数
    public function quGeDanwei(){
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => array()
                ,'betweentime'
                ,'category_ryc'
            ], 'POST');
        // 获取课题列表
        $tj = new tj();
        $data = $tj->quGeDanwei($src);
        return json($data);
    }
}
