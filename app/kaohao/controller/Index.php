<?php
namespace app\kaohao\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

// 引用数据模型类
use app\kaoshi\model\Kaoshi as KS;
use app\kaoshi\model\KaoshiSet as ksset;
use app\kaohao\model\Kaohao as KH;
use app\student\model\Student as stu;


class Index extends AdminBase
{
    // 生成考号
    public function createAll($kaoshi_id)
    {
        // 获取参考年级
        $ksset = new ksset;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        event('kslu', $src['kaoshi_id']);
        $list['data']['nianji'] = $ksset->srcGrade($src);
        $list['data']['nianjiNum'] = array_column($list['data']['nianji'], 'ruxuenian');

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


    // 保存考号
    public function saveAll()
    {
        // 获取表单数据
        $list = request()->only([
            'school_id'
            ,'kaoshi_id'
            ,'banji_id'
        ], 'POST');

        event('kslu', $list['kaoshi_id']);
        // 验证表单数据
        $validate = new \app\kaohao\validate\Kaohao;
        $result = $validate->scene('createAll')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return ['msg' => $msg, 'val' => 0];
        }

        // 获取参加考试学生名单
        $stu = new \app\student\model\Student;
        $src = [
            'banji_id' => $list['banji_id']
            ,'kaoshi' => 1
            ,'status' => 1
            ,'all' => true
        ];

        $src['auth'] = event('mybanji', array());
        $src['auth'] = $src['auth'][0];
        $src['auth']['banji_id'] = array_intersect($src['auth']['banji_id'], $src['banji_id']);

        $stulist = $stu->search($src)
            ->visible([
                'id'
                ,'school_id'
                ,'banji_id'
                ,'stuBanji' => [
                    'id'
                    ,'ruxuenian'
                    ,'paixu'
                ]
            ]);

        // 获取参加考试年级数组
        $enddate = KS::where('id', $list['kaoshi_id'])->value('enddate');
        $njlist =  \app\facade\Tools::nianJiNameList($enddate);

        // 重新组合学生信息
        $kaohao = array();
        foreach ($stulist as $key => $value) {
            $check = KH::withTrashed()
                ->where('kaoshi_id', $list['kaoshi_id'])
                ->where('student_id', $value->id)
                ->find();
            if($check)  # 恢复后学生还是原来的班级,删除后重新添加可能会出错
            {
                if($check->delete_time > 0)
                {
                    $check->restore();
                }
                if($check->status == 0)
                {
                    $check->status = 1;
                    $check->save();
                }

                $stuInfo = stu::where('id', $value->id)
                    ->field('id, banji_id')
                    ->with([
                        'stuBanji'=>function($query){
                            $query
                                ->field('id, ruxuenian, paixu, school_id')
                                ->append(['banjiTitle', 'grade']);
                        }
                    ])
                    ->find();
                $check->school_id = $stuInfo->stuBanji->school_id;
                $check->ruxuenian =  $stuInfo->stuBanji->ruxuenian;
                $check->nianji =  $stuInfo->stuBanji->grade;
                $check->banji_id =  $stuInfo->banji_id;
                $check->paixu =  $stuInfo->stuBanji->paixu;
                $check->status =  1;
                $check->save();

                continue;
            }
            $kaohao[$key]['student_id'] = $value->id;
            $kaohao[$key]['school_id'] = $list['school_id'];
            $kaohao[$key]['ruxuenian'] = $value->stuBanji->ruxuenian;
            $kaohao[$key]['nianji'] = $njlist[$kaohao[$key]['ruxuenian']];
            $kaohao[$key]['banji_id'] = $value->stuBanji->id;
            $kaohao[$key]['paixu'] = $value->stuBanji->paixu;
            $kaohao[$key]['kaoshi_id'] = $list['kaoshi_id'];
        }

        // 保存考号
        $kh = new KH;
        $data = $kh
            ->allowField([ # 不是最佳实践
                'id'
                ,'kaoshi_id'
                ,'student_id'
                ,'school_id'
                ,'ruxuenian'
                ,'nianji'
                ,'banji_id'
                ,'paixu'
                ,'create_time'
                ,'update_time'
            ])
            ->saveAll($kaohao);
        $cnt = $data->count();

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '成功生成' . $cnt . '个考号。', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 添加单条考号
    public function create($kaoshi_id)
    {
        // 获取参考年级、学科
        $ksset = new ksset;
        $src = [
            'kaoshi_id' => $kaoshi_id
            ,'all' => true
        ];
        event('kslu', $src['kaoshi_id']);
        $list['data']['nianji'] = $ksset->srcGrade($src);
        $kh = new KH;
        if(count($list['data']['nianji']) > 0){
            $src['ruxuenian'] = $list['data']['nianji'][0];
        } else {
            $src['ruxuenian'] = array();
        }

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '添加考号'
            ,'butname' => '添加'
            ,'formpost' => 'POST'
            ,'url' => '/kaohao/index/save'
            ,'kaoshi_id' => $kaoshi_id
        );

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch();
    }


    // 保存单条考号
    public function save()
    {
        // 获取表单数据
        $list = request()->only([
            'kaoshi_id'
            ,'school_id'
            ,'ruxuenian'
            ,'nianji'
            ,'banji_id'
            ,'paixu'
            ,'student_id'
        ], 'POST');

        event('kslu', $list['kaoshi_id']);

        // 验证表单数据
        $validate = new \app\kaohao\validate\Kaohao;
        $result = $validate->scene('create')->check($list);

        // 查询考号是否存在
        $ks = KH::withTrashed()
                ->where('kaoshi_id', $list['kaoshi_id'])
                ->where('student_id', $list['student_id'])
                ->find();

        $list['student_id'] = explode(',', $list['student_id']);
        $temp = array();
        foreach ($list['student_id'] as $key => $value) {
            $temp = [
                'kaoshi_id' => $list['kaoshi_id']
                ,'school_id' => $list['school_id']
                ,'ruxuenian' => $list['ruxuenian']
                ,'nianji' => $list['nianji']
                ,'banji_id' => $list['banji_id']
                ,'paixu' => $list['paixu']
                ,'student_id' => $value
            ];

            $ks = KH::withTrashed()
                ->where('kaoshi_id', $temp['kaoshi_id'])
                ->where('student_id', $temp['student_id'])
                ->find();

            // 如果存在成绩则更新，不存在则添加
            if($ks)
            {
                // 判断记录是否被删除
                if($ks->delete_time > 0)
                {
                    $ks->restore();
                }
                $stuInfo = stu::where('id', $temp['student_id'])
                    ->field('id, banji_id')
                    ->with([
                        'stuBanji'=>function($query){
                            $query
                                ->field('id, ruxuenian, paixu, school_id')
                                ->append(['banjiTitle', 'grade']);
                        }
                    ])
                    ->find();
                $ks->school_id = $stuInfo->stuBanji->school_id;
                $ks->ruxuenian =  $stuInfo->stuBanji->ruxuenian;
                $ks->nianji =  $stuInfo->stuBanji->grade;
                $ks->banji_id =  $stuInfo->banji_id;
                $ks->paixu =  $stuInfo->stuBanji->paixu;
                $ks->status =  1;
                $ks->save();
                $data = ['msg' => '生成成功', 'val' => 1];
            }else{
                $data = KH::create($temp);
            }

        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '生成成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 修改考号信息
    public function edit($id)
    {
        // 获取考试信息
        $list['data'] = KH::where('id', $id)
            ->field('id, ruxuenian, nianji, banji_id, paixu, student_id, kaoshi_id')
            ->with([
                'cjStudent' => function($query) {
                    $query->field('id, xingming, banji_id')
                        ->with([
                            'stuBanji'=>function($query){
                                $query
                                    ->field('id, ruxuenian, paixu, alias, school_id')
                                    ->with([
                                        'glSchool' => function($query){
                                            $query->field('id, title, jiancheng');
                                        },
                                    ])
                                    ->append(['banjiTitle']);
                            }
                        ]);
                }
                ,'cjBanji'
            ])
            ->append(['banjiTitle'])
            ->find();

        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '编辑考号'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/kaohao/index/update/' . $id
        );


        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('edit');
    }


    // 更新考试信息
    public function update($id)
    {
        
        // 获取表单数据
        $list = request()->only([
            'banji_id',
            'kaoshi_id',
            'school_id',
        ], 'post');
        $list['id'] = $id;
        event('kslu', $list['kaoshi_id']);

        $bj = new \app\teach\model\Banji;
        $bjInfo = $bj->where('id', $list['banji_id'])->find();

        $list['ruxuenian'] = $bjInfo->ruxuenian;
        $list['paixu'] = $bjInfo->paixu;

        $ks = new \app\kaoshi\model\Kaoshi;
        $bfdate = $ks->where('id', $list['kaoshi_id'])->value('bfdate');
        $njlist = \app\facade\Tools::nianJiNameList($bfdate);
        $njlistNow = \app\facade\Tools::nianJiNameList($bfdate);
        if ($njlist[$list['ruxuenian']] != $njlistNow[$list['ruxuenian']]) {
            return json(['msg' => '已经跨学年了，不建议修改。', 'val' => 0]);
        }
        $list['nianji'] = $njlist[$list['ruxuenian']];
        

        // 验证表单数据
        $validate = new \app\kaohao\validate\Kaohao;
        $result = $validate->scene('edit')->check($list);
        $msg = $validate->getError();
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);
        }

        // 更新数据
        $kh = new KH();
        $khdata = $kh::update($list);
        $khdata ? $data = ['msg' => '更新成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    // 学生信息
    public function read($id)
    {
        $kh = new \app\kaohao\model\Kaohao;
        $khInfo = $kh->where('id', $id)
            ->with([
                'cjKaoshi' => function ($query) {
                    $query->field('id, title, bfdate, enddate');
                }
            ])
            ->find();
        $list['webtitle'] = $khInfo->cjKaoshi->title;
        $list['id'] = $khInfo->id;

        // 模板赋值
        $this->view->assign('list', $list);

        return $this->view->fetch();
    }


    // 删除考号
    public function delete()
    {
        // 整理数据
        $id = request()->delete('id');
        $id = explode(',', $id);

        if(isset($id[0]))
        {
            $ksid = KH::where('id', $id[0])->value('kaoshi_id');
            event('kslu', $ksid);
            $data = KH::destroy($id);
        }else{
            $data = false;
        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
    // 返回信息
        return json($data);
    }
}
