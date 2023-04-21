<?php
declare (strict_types = 1);

namespace app\Kaoshi\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

class TongjiLog extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($kaoshi_id)
    {
        // 获取考试信息
        $ks = new \app\kaoshi\model\Kaoshi;
        $ksinfo = $ks->where('id', $kaoshi_id)
            ->field('id, title')
            ->find();

        // 设置要给模板赋值的信息
        $list['webtitle'] = '最新统计结果状态';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['kaoshititle'] = $ksinfo->title;
        $list['dataurl'] = '/kaoshi/tjlog/data';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    // 获取数据
    public function ajaxData()
    {
        $src = $this->request
            ->only([
                'kaoshi_id' => '',
            ], 'POST');

        // 实例化考号
        $khSrc = new \app\kaohao\model\SearchMore;
        $lastcj = $khSrc->lastUpdateTime($src['kaoshi_id']);
        $cjLastTime = $lastcj->getData('update_time');
        $src['cjlast'] = $cjLastTime;

        $log = new \app\kaoshi\model\TongjiLog;
        $data = $log->search($src);
        $src['all'] = true;
        $cnt = $log->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }
}
