<?php
declare (strict_types = 1);

namespace app\keti\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用课题信息数据模型类
use app\keti\model\Tongji as tj;

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
        $list['webtitle'] = '课题统计';
        $list['dataurl'] = '/kaoshi/index/data';
        $list['status'] = '/kaoshi/index/status';
        $list['luru'] = '/kaoshi/index/luru';

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染模板
        return $this->view->fetch();
    }


    // 获取区各级立项课题数
    public function quGejiLixiang(){
    	// 获取参数
        $src = $this->request
            ->only([
				'searchval' => array()
                ,'betweentime'
            ], 'POST');
        // 获取课题列表
        $tj = new tj();
        $data = $tj->quGejiLixiang($src);
        return json($data);
    }


    // 获取区各单位立项课题数
    public function quGeDanweiLixiang(){
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => array()
                ,'betweentime'
            ], 'POST');
        // 获取课题列表
        $tj = new tj();
        $data = $tj->quGeDanweiLixiang($src);
        return json($data);
    }


    // 获取区各级立项课题数
    public function quGejiJieti(){
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => array()
                ,'betweentime'
            ], 'POST');
        // 获取课题列表
        $tj = new tj();
        $data = $tj->quGejiJieti($src);
        return json($data);
    }


    // 获取区各单位立项课题数
    public function quGeDanweiJieti(){
        // 获取参数
        $src = $this->request
            ->only([
                'searchval' => array()
                ,'betweentime'
            ], 'POST');
        // 获取课题列表
        $tj = new tj();
        $data = $tj->quGeDanweiJieti($src);
        return json($data);
    }
}
