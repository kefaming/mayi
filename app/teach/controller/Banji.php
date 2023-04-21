<?php

namespace app\teach\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用班级数据模型类
use app\teach\model\Banji as bjmod;

class Banji extends AdminBase
{
    /**
     * 显示班级列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '班级列表';
        $list['dataurl'] = 'banji/data';
        $list['status'] = '/teach/banji/status';

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch();
    }


    /**
     * 获取班级列表数据
     *
     * @return \think\Response
     */
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'paixu'
                ,'order' => 'asc'
                ,'school_id' => ''
                ,'ruxuenian' => ''
            ], 'POST');

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];

        // 查询数据
        $bj = new bjmod;
        $data = $bj->search($src)
            ->visible([
                'id'
                ,'glSchool' => ['title']
                ,'ruxuenian'
                ,'school_id'
                ,'paixu'
                ,'gl_student_count'
                ,'status'
                ,'alias'
                ,'update_time'
            ]);  # 查询数据
        $src['all'] = true;
        $cnt = $bj->search($src)->count();
        $data = reset_data($data, $cnt);

        // 返回数据
        return json($data);
    }


    // 显示学生列表
    public function student($banji_id)
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '学生列表';
        $list['dataurl'] = '/student/index/data';
        $list['status'] = '/student/index/status';
        $list['kaoshi'] = '/student/index/kaoshi';
        $list['banji_id'] = $banji_id;

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch('student@index/index');
    }


    /**
     * 创建班级
     *
     * @return \think\Response
     */
    public function create()
    {
        $alias = \app\facade\System::sysInfo();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加班级'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => 'save'
            ,'classmax' =>$alias->classmax
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('create');
    }


    /**
     * 保存班级
     *
     * @return \think\Response
     */
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'school_id'
            ,'ruxuenian'
            ,'bjsum'
        ], 'post');

        // 验证表单数据
        $validate = new \app\teach\validate\Banji;
        $result = $validate->scene('create')->check($list);
        $msg = $validate->getError();
        if(!$result)
        {
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 整理要添加的数据
        $paixumax = bjmod::where('school_id', $list['school_id'])
            ->where('ruxuenian', $list['ruxuenian'])
            ->max('paixu');

        // 获取班级最大数
        $cnfMax = \app\facade\System::sysInfo();

        if($paixumax + $list['bjsum'] > $cnfMax->classmax) # 如果增加班级数超过2个，则少加班级
        {
            if($paixumax >= $cnfMax->classmax)
            {
                return json(['msg' => '已经超过同年级班级数的上线啦。', 'val' => 0]);
            }
            if($paixumax + $list['bjsum'] > $cnfMax->classmax) # 如果增加班级数超过2个，则少加班级
            {
                $list['bjsum'] = $cnfMax->classmax - $paixumax;
            }
        }

        $i = 1;
        while($i <= $list['bjsum'])
        {
            $bjarr[] = array(
                'school_id' => $list['school_id'],
                'ruxuenian' => $list['ruxuenian'],
                'paixu' => $paixumax + $i,
            );
            $i ++;
        }

        // 保存数据
        $bj = new bjmod();
        $data = $bj->saveAll($bjarr);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '添加成功', 'val'=>1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 删除班级
     *
     * @return \think\Response
     */
    public function delete()
    {

        $id = request()->delete('id');
        $id = explode(',', $id);

        $data = bjmod::destroy($id);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 设置班级状态
     *
     * @return \think\Response
     */
    public function setStatus()
    {

        //  获取id变量
        $id = request()->post('id');
        $value = request()->post('value');

        // 更新状态
        $data = bjmod::where('id', $id)->update(['status' => $value]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '状态设置成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
     * 班级移动
     *
     * @return \think\Response
     */
    public function yidong($id)
    {
        // 获取操作参数
        $caozuo = input('post.cz');

        // 获取当前班级信息
        $thisbj = bjmod::find($id);

        // 获取相邻两个班级信息
        if( $caozuo > 0 )
        {
            $bjinfo = bjmod::withTrashed()
                ->where('school_id', $thisbj->getData('school_id'))
                ->where('ruxuenian', $thisbj->ruxuenian)
                ->where('paixu', '>=', $thisbj->paixu)
                ->order(['paixu'])
                ->limit('2')
                ->field('id, paixu, ruxuenian')
                ->append(['banjiTitle'])
                ->select();
        }else{
            $bjinfo = bjmod::withTrashed()
                ->where('school_id', $thisbj->getData('school_id'))
                ->where('ruxuenian', $thisbj->ruxuenian)
                ->where('paixu', '<=', $thisbj->paixu)
                ->order(['paixu' => 'desc'])
                ->limit('2')
                ->field('id, paixu, ruxuenian')
                ->append(['banjiTitle'])
                ->select();
        }

        // 交换班级排序
        if($bjinfo->count() == 2)
        {
            // 交换两个班级的排序
            $data[] = [
                'id' => $bjinfo[0]->id,
                'paixu' => $bjinfo[1]->paixu,
            ];
            $data[] = [
                'id' => $bjinfo[1]->id,
                'paixu' => $bjinfo[0]->paixu,
            ];

            // 更新信息
            $bj = new bjmod;
            $data = $bj->saveAll($data);

            $data ? $data = ['msg' => '移动成功', 'val' => 1]
                : $data = ['msg' => '数据处理错误', 'val' => 0];
        }else{
            $data = ['msg' => '已经到头啦~', 'val' => 0];
        }

        // 返回处理结果
        return json($data);
    }


    /**
     * 获取班级信息
     *
     * @return \think\Response
     */
    public function mybanji()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'school_id' => ''
                ,'ruxuenian' => ''
                ,'status' => 1
                ,'limit' =>100
                ,'auth' => array()
            ], 'POST');

        if($src['school_id'] == '' && $src['ruxuenian'] == '')
        {
            return '';
        }

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];

        // 查询班级
        $bj = new bjmod;
        $data = $bj->search($src);  # 查询数据
        $data = $data->order('paixu','asc')
            ->visible([
                'id'
                ,'banTitle'
                ,'banjiTitle'
                ,'paixu'
                ,'glSchool' => [
                    'title'
                    ,'jiancheng'
                ]
            ])
            ->toArray();
        $src['all'] = true;
        $cnt = $bj->search($src)->count();
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // /**
    //  * 获取班级信息
    //  *
    //  * @return \think\Response
    //  */
    // public function banjiList()
    // {
    //     // 获取参数
    //     $src = $this->request
    //         ->only([
    //             'school_id' => ''
    //             ,'ruxuenian' => ''
    //             ,'status' => 1
    //             ,'limit' =>100
    //         ], 'POST');

    //     // 查询班级
    //     $bj = new bjmod;
    //     $list = $bj->searchNjGroup($src)
    //         ->visible([
    //             'ruxuenian'
    //             ,'njBanji' => [
    //                 'id'
    //                 ,'banjiTitle'
    //                 ,'banTitle'
    //             ]
    //         ]);  # 查询数据
    //     $data = reset_data($list, $src);

    //     // 返回数据
    //     return json($list);
    // }


    // 设置班级别名
    public function setAlias()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'id' => '',
                'alias' => '',
            ], 'PUT');

        // 验证表单数据
        $validate = new \app\teach\validate\Banji;
        $result = $validate->scene('alias')->check($src);
        $msg = $validate->getError();
        if(!$result)
        {
            return json(['msg' => $msg, 'val' => 0]);
        }

        $sysInfo = \app\facade\System::sysInfo();
        if($sysInfo->classalias == false)
        {
            return json(['msg' => '不允许使用班级别名', 'val' => 0]);
        }

        // 保存数据
        $bj = new bjmod();

        // 更新数据
        $bjlist = bjmod::find($src['id']);
        $bjlist->alias = $src['alias'];
        $data = $bjlist->save();

        // 根据更新结果设置返回提示信息
        $data >= 0 ? $data = ['msg' => '别名设置成功', 'val' => 1]
            : $data = ['msg' => '别名设置失败', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 根据考试结束时间获取当时的年级列表
    public function njList()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'riqi' => ''
                ,'category' => 'str'
            ], 'POST');

        $bj = new bjmod;
        $njList = $bj->gradeName($src['riqi'], $src['category']);
        $cnt = count($njList);
        $njList = reset_data($njList, $cnt);

        return json($njList);
    }

}
