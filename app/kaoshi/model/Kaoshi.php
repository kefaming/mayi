<?php

namespace app\kaoshi\model;

// 引用数据模型基类
use \app\BaseModel;


class Kaoshi extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'zuzhi_id' => 'int'
        ,'xueqi_id' => 'int'
        ,'category_id' => 'int'
        ,'user_id' => 'int'
        ,'fanwei_id' => 'int'
        ,'bfdate' => 'int'
        ,'enddate' => 'int'
        ,'status' => 'tinyint'
        ,'luru' => 'status'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];


    // 查询所有考试
    public function search($srcfrom)
    {
        $src = [
            'id' => array()
            ,'zuzhi_id' => array()
            ,'xueqi_id' => ''
            ,'category_id' => array()
            ,'luru' => ''
            ,'status' => ''
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['zuzhi_id'] = str_to_array($src['zuzhi_id']);
        $src['xueqi_id'] = str_to_array($src['xueqi_id']);
        $src['category_id'] = str_to_array($src['category_id']);
        $src['id'] = str_to_array($src['id']);

        // 查询数据
        $data = $this
            ->order([$src['field'] =>$src['order']])
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title', 'like', '%' . $src['searchval']. ' %');
                })
            ->when(count($src['zuzhi_id']) > 0, function($query) use($src){
                    $query->where('zuzhi_id', 'in', $src['zuzhi_id']);
                })
            ->when(count($src['xueqi_id']) > 0, function($query) use($src){
                    $query->where('xueqi_id', 'in', function($q)use($src){
                        $q->name('xueqi')
                            ->where('category_id', 'in', $src['xueqi_id'])
                            ->field('id');
                    });
                })
            ->when(count($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', 'in', $src['category_id']);
                })
            // ->when(session('user_id') !=1 && session('user_id') !=2, function($query) use($src){
            //         $query->where('id', 'in', $src['id']);
            //     })
            // ->when(session('user_id') !=1 && session('user_id') !=2, function($query) use($src){
            //         $query->whereOr('user_id', session('user_id'));
            //     })
            ->when(strlen($src['luru']) > 0, function ($query) use($src) {
                    $query->where('luru', $src['luru']);
            })
            ->when(strlen($src['status']) > 0, function ($query) use($src) {
                    $query->where('status', $src['status']);
            })
            ->with(
                [
                    'ksCategory' => function ($query) {
                        $query->field('id, title');
                    }
                    ,'ksZuzhi' => function ($query) {
                        $query->field('id, title, jiancheng');
                    }
                    ,'ksXueqi' => function ($query) {
                        $query->field('id, title');
                    }
                    ,'ksFanwei' => function ($query) {
                        $query->field('id, title');
                    }
                    ,'ksTeahcer' => function ($query) {
                        $query->field('id, xingming');
                    }
                ]
            )
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->append(['ckSubject', 'ckNianji'])
            ->select();

        return $data;
    }


    // 查询教师可以查看的考试
    public function srcAuth()
    {
        $banji_id = array();
        $qxBanjiIds = event('mybanji', array());
        if (is_array($qxBanjiIds[0])) {
            $banji_id = $qxBanjiIds[0]['banji_id'];
        }
        $kh = new \app\kaohao\model\Kaohao;
        $data = $kh
            ->whereOr('banji_id', 'in', $banji_id)
            ->distinct(true)
            ->field('kaoshi_id')
            ->select();
        $kaoshi_ids = $data->column('kaoshi_id');

        // 查一下任课情况
        $btj = new \app\chengji\model\TongjiBj;
        $krids = $btj
            ->where('teacher_id', session('id'))
            ->distinct(true)
            ->field('kaoshi_id')
            ->column(['kaoshi_id']);
        $ids = $this->where('user_id', session('user_id'))->column(['id']);
        $kaoshi_ids =array_unique(array_merge($kaoshi_ids, $ids, $krids));

        return $kaoshi_ids;
    }


    // 本次考试信息，包含ID、标题、状态、是否允许编辑成绩
    public function kaoshiInfo($id = 0)
    {
        // 获取参考年级
        $kaoshiList = $this->where('id', $id)
                ->field('id, title, user_id, status, luru, bfdate, enddate')
                ->cache('kaoshiinfo')
                ->find();
        return $kaoshiList;
    }


    // 开始时间修改器
    public function setBfdateAttr($value)
    {
        return strtotime($value);
    }


    // 开始时间获取器
    public function getBfdateAttr($value)
    {
        return date('Y-m-d', $value);
    }


    // 结束时间修改器
    public function setEnddateAttr($value)
    {
        // 设置结束时间为当年的最后1秒
        $sj = $value . ' 23:59:59';
        return strtotime($sj);
    }


    // 结束时间获取器
    public function getEnddateAttr($value)
    {
        return date('Y-m-d', $value);
    }


    // 参考学科获取器
    public function getCkSubjectAttr($value)
    {
        // 查询参考学科
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $this->getAttr('id')
        ];
        $subject = $ksset->srcSubject($src);
        // 重新整理数据
        $str = '';
        if(isset($subject))
        {
            $i = 0;
            foreach ($subject as $key => $value) {
                if($i == 0)
                {
                    $str = $value['jiancheng'];
                }else{
                    $str = $str . '、' . $value['jiancheng'];
                }
                $i = $i + 1;
            }
        }

        return $str;
    }


    // 参考年级获取器
    public function getCkNianjiAttr($value)
    {
        // 查询参考年级
        $ksset = new \app\kaoshi\model\KaoshiSet;
        $src = [
            'kaoshi_id' => $this->getAttr('id')
            ,'all' => true
        ];
        $data = $ksset->srcGrade($src);
        // 重新整理数据
        $str = '';
        if(isset($data))
        {
            $i = 0;
            foreach ($data as $key => $value) {
                $temp = substr($value['nianjiname'], 0, 3);
                if($i == 0)
                {
                    $str = $temp;
                }else{
                    $str = $str . '、' . $temp;
                }
                $i = $i + 1;
            }
        }
        return $str;
    }


    // 考试设置关联表
    public function ksSet()
    {
        return $this->hasMany(KaoshiSet::class, 'kaoshi_id', 'id');
    }


    // 参考类别关联表
    public function ksCategory()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }


    // 考试成绩关联表
    public function ksChengji()
    {
        return $this->hasMany(\app\chengji\model\Chengji::class, 'kaoshi_id', 'id');
    }


    // 考试组织单位关联表
    public function ksZuzhi()
    {
        return $this->belongsTo(\app\system\model\School::class, 'zuzhi_id', 'id');
    }


    // 学期
    public function ksXueqi()
    {
        return $this->belongsTo(\app\teach\model\Xueqi::class, 'xueqi_id', 'id');
    }


    // 学期
    public function ksFanwei()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'fanwei_id', 'id');
    }


    // 学期
    public function ksTeahcer()
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'user_id', 'id');
    }

}
