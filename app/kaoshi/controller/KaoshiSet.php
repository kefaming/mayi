<?php
namespace app\kaoshi\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

// 引用考号数据模型类
use app\kaoshi\model\KaoshiSet as ksset;


class KaoshiSet extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    // 显示考试列表
    public function index($kaoshi_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '参加考试学科';
        $list['dataurl'] = '/kaoshi/kaoshiset/data';
        $list['kaoshi_id'] = $kaoshi_id;
        $list['status'] = '/kaoshi/kaoshiset/status';

        $ksset = new ksset;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        // $list['subject'] = $ksset->srcSubject($src);
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
                ,'ruxuenian' => array()
                ,'subject_id' => array()
                ,'page' => '1'
                ,'limit' => '10'
                ,'field' => 'subject_id'
                ,'order' => 'asc'
                ,'searchval' => ''
            ], 'POST');

        // 根据条件查询数据
        $ksset = new ksset;
        $data = $ksset->search($src);
        $src['all'] = true;
        $cnt = $ksset->search($src)->count();
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
            'webtitle' => '设置考试'
            ,'butname' => '设置'
            ,'formpost' => 'POST'
            ,'url' => '/kaoshi/kaoshiset/save'
            ,'kaoshi_id' => $kaoshi_id
            ,'all' => true
        );

        // 获取考试时间
        $ks = new \app\kaoshi\model\Kaoshi;
        $enddate = $ks->kaoshiInfo($kaoshi_id);
        $list['enddate'] = $enddate->getData('enddate');
        // $list['set']['nianji'] = nianJiNameList('str', $enddate);

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
                    ,'ruxuenian'
                    ,'nianjiname'
                    ,'subject_id' => array()
                    ,'manfen' => array()
                    ,'youxiu' => array()
                    ,'lianghao' => array()
                    ,'jige' => array()
                    ,'youxiubi' => array()
                    ,'lianghaobi' => array()
                    ,'jigebi' => array()
                ], 'POST');

        event('kslu', $src['kaoshi_id']);

        // 验证表单数据
        $validate = new \app\kaoshi\validate\Kaoshiset;
        $result = $validate->scene('create')->check($src);
        $msg = $validate->getError();
        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg,'val' => 0]);;
        }

        // 整理参数
        $list = array();
        foreach ($src['subject_id'] as $key  =>  $value) {
            $list[] = [
                'kaoshi_id' => $src['kaoshi_id'],
                'ruxuenian' => $src['ruxuenian'],
                'nianjiname' => $src['nianjiname'],
                'subject_id' => $value,
                'manfen' => $src['manfen'][$key],
                'youxiu' => $src['youxiu'][$key],
                'lianghao' => $src['lianghao'][$key],
                'jige' => $src['jige'][$key],
                'youxiubi' => $src['youxiubi'][$key],
                'lianghaobi' => $src['lianghaobi'][$key],
                'jigebi' => $src['jigebi'][$key],
            ];
        }

        // 查询已经存在数据删除并添加新数据
        $ksset = new ksset;
        $ruxuenian = $src['ruxuenian'];
        $kaoshi_id = $src['kaoshi_id'];

        ksset::destroy(function($query)use($ruxuenian, $kaoshi_id){
            $query->where('kaoshi_id', $kaoshi_id)
                ->where('ruxuenian', $ruxuenian);
            ;
        },true);

        $data = $ksset->saveAll($list);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);

    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // 获取考试信息
        $ksset = new ksset;
        $list['data'] = $ksset::where('id',$id)
            ->field('id, kaoshi_id, nianjiname, subject_id, manfen, youxiu, lianghao, jige, youxiubi, lianghaobi, jigebi')
            ->with([
                'subjectName' => function($query){
                    $query->field('id, title, jiancheng');
                }
            ])
            ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle'=>'编辑考试设置'
            ,'butname'=>'修改'
            ,'formpost'=>'PUT'
            ,'url'=>'/kaoshi/kaoshiset/update/'.$id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('edit');
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {
        // 获取表单数据
        $list = request()->only([
            'id'
            ,'kaoshi_id'
            ,'manfen'
            ,'youxiu'
            ,'lianghao'
            ,'jige'
            ,'youxiubi'
            ,'lianghaobi'
            ,'jigebi'
        ], 'POST');

        // 验证表单数据
        $validate = new \app\kaoshi\validate\KaoshiSetEdit;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }
        event('kslu', $list['kaoshi_id']);

        // 更新数据
        $ksset = new ksset();
        $ksdata = $ksset::update($list);

        // 根据更新结果设置返回提示信息
        $ksdata ? $data = ['msg' => '更新成功', 'val' => 1]
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

        // 判断考试结束时间是否已过
        $ksset = new ksset;
        $ksid = $ksset::where('id', $id[0])->value('kaoshi_id');
        event('kslu', $ksid);

        $data = ksset::destroy($id,true);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功','val' => 1]
            : $data = ['msg' => '数据处理错误','val' => 0];
        // 返回信息
        return json($data);
    }


    // 设置荣誉状态
    public function setStatus()
    {
        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        $ksid = ksset::where('id', $id)->value('kaoshi_id');
        event('kslu', $ksid);

        // 获取学生信息
        $data = ksset::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data= [ 'msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }

}
