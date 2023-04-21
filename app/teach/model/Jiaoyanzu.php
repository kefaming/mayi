<?php

namespace app\teach\model;

use app\BaseModel;


/**
 * @mixin \think\Model
 */
class Jiaoyanzu extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'ruxuenian' => 'int'
        ,'category_id' => 'int'
        ,'school_id' => 'int'
        ,'subject_id' => 'varchar'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
        ,'status' => 'tinyint'
    ];


    // 分类关联
    public function glCategory()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }


    // 教研组关联
    public function glSchool()
    {
        return $this->belongsTo(\app\system\model\School::class, 'school_id', 'id');
    }


    // 根据条件查询教研组
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'category_id' => ''
            ,'searchval' => ''
            ,'school_id' => ''
            ,'status' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src) ;
        $src['school_id'] = str_to_array($src['school_id']);

        $njlist = \app\facade\Tools::nianJiNameList(time(), 'num');

        // 查询数据
        $data = $this
            ->where(function ($query) use($njlist) {
                $query
                    ->whereOr('ruxuenian', 'in', $njlist)
                    ->whereOr('ruxuenian', null);
            })
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title|xuenian', 'like', '%' . $src['searchval'] . '%');
                })
            ->when(strlen($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', $src['category_id']);
                })
            ->when(count($src['school_id']) > 0, function($query) use($src){
                    $query->where('school_id', 'in', $src['school_id']);
                })
            ->when(strlen($src['status']) > 0, function($query) use($src){
                    $query->where('status', $src['status']);
                })
            ->with(
                [
                    'glCategory'=>function($query){
                        $query->field('id, title');
                    },
                    'glSchool'=>function($query){
                        $query->field('id, title');
                    },
                ]
            )
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->append(['zuzhang'])
            ->select();

        return $data;
    }


    // 查询教研组信息
    public function oneInfo($id) {
        $data = $this
            ->where('id', $id)
            ->field('id, school_id, ruxuenian, category_id, subject_id')
            ->append(['banjiId'])
            ->find();
        return $data;
    }


    // 学科修改器
    public function setsubjectIdAttr($value)
    {
        $value = implode('|', $value);
        return $value;
    }


    // 学科获取器
    public function getsubjectTitleAttr($value)
    {
        $value = $this->subject_id;
        $value = explode('|', $value);
        $subject = new \app\teach\model\Subject;
        $list = $subject
            ->where('id', 'in', $value)
            ->field('id, title, jiancheng')
            ->select();
        $str = '';
        foreach ($list as $key => $value) {
            $key == 0 ? $str = $value->title : $str = $str . '、'. $value->title;
        }
        return $str;
    }


    // 获取教研组名
    public function getTitleAttr($value)
    {
        $title = $value;
        if($this->category_id == 12501)
        {
            $njlist = \app\facade\Tools::nianJiNameList('str');
            $title = $njlist[$this->ruxuenian] . '组';
        }
        return $title;
    }


    // 获取组长
    public function getZuzhangAttr($value)
    {
        $zh = new \app\teach\model\JiaoyanZuzhang;
        $zhInfo = $zh->where('jiaoyanzu_id', $this->id)
            ->where('bfdate', '<=', time())
            ->order(['bfdate' => 'desc'])
            ->with([
                'glTeacher' => function ($query) {
                    $query->field('id, xingming');
                }
            ])
            ->find();

        $str = ['id', 'xingming'];
        if ($zhInfo) {
            $str = [
                'id' => $zhInfo->teacher_id
                ,'xingming' => $zhInfo->glTeacher->xingming
            ];
        }

        return $str;
    }


    // 班级获取器
    public function getBanjiIdAttr($value)
    {
        $bjList = array();
        if ($this->category_id == 12501) {
            $bj = new \app\teach\model\Banji;
            $bjList = $bj
                ->where('school_id', $this->school_id)
                ->where('ruxuenian', $this->ruxuenian)
                ->column('id');
        }
        return $bjList;
    }


    // 班级获取器
    public function getSubjectIdAttr($value)
    {
        $list = array();
        if ($this->category_id == 12502 and is_null($value) == false) {
            $list = explode('|', $value);
        }
        return $list;
    }
}
