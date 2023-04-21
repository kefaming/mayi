<?php

namespace app\kaoshi\model;

// 引用数据模型基类
use \app\BaseModel;

class KaoshiSet extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'kaoshi_id' => 'int'
        ,'ruxuenian' => 'int'
        ,'nianjiname' => 'varchar'
        ,'subject_id' => 'int'
        ,'manfen' => 'int'
        ,'youxiu' => 'int'
        ,'lianghao' => 'int'
        ,'jige' => 'int'
        ,'youxiu' => 'int'
        ,'lianghaobi' => 'int'
        ,'jigebi' => 'int'
        ,'status' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 关联学科
    public function subjectName()
    {
    	return $this->belongsTo(\app\teach\model\Subject::class, 'subject_id', 'id');
    }


    // 查询参加考试学科
    public function search($srcfrom = array())
    {
    	// 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'ruxuenian' => array()
            ,'subject_id' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        );
        $src = array_cover($srcfrom, $src);
        $src['ruxuenian'] = str_to_array($src['ruxuenian']);
        $src['subject_id'] = str_to_array($src['subject_id']);

        $data = $this->where('kaoshi_id', $src['kaoshi_id'])
            ->when(count($src['ruxuenian']) > 0, function($query) use ($src) {
                $query->where('ruxuenian', 'in', $src['ruxuenian']);
            })
            ->when(count($src['subject_id']) > 0, function($query) use ($src) {
                $query->where('subject_id', 'in', $src['subject_id']);
            })
        	->with([
        		'subjectName' => function($query){
        			$query->field('id, title, jiancheng');
        		}
        	])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
        	->select();

        return $data;

    }


    /**
     * 查询参加考试学科
     *
     * @return \think\Response
     */
    // 显示考试列表
    public function srcSubject($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'ruxuenian' => 0
            ,'subject_id' => array()
        );

        $src = array_cover($srcfrom, $src);
        $src['subject_id'] = str_to_array($src['subject_id']);

        $sbjList = $this->where('kaoshi_id', $src['kaoshi_id'])
            ->where('status', 1)
            ->when(count($src['subject_id']) > 0, function ($query) use ($src) {
                $query->where('subject_id', 'in', $src['subject_id']);
            })
            ->when($src['ruxuenian'] > 0, function ($query) use ($src) {
                $query->where('ruxuenian', $src['ruxuenian']);
            })
            ->group('subject_id')
            ->field("subject_id
                ,any_value(manfen) as manfen
                ,any_value(youxiu) as youxiu
                ,any_value(jige) as jige")
            ->with([
                'subjectName' => function ($query) {
                    $query->field('id, title, jiancheng, paixu, lieming');
                }
            ])
            ->select();

        // 重新整理学科信息
        $data = array();
        foreach ($sbjList as $key => $value) {
            # code...
            $data[$value->subjectName->id] = [
                'id' => $value->subjectName->id,
                'title' => $value->subjectName->title,
                'jiancheng' => $value->subjectName->jiancheng,
                'paixu' => $value->subjectName->paixu,
                'lieming' => $value->subjectName->lieming,
                'fenshuxian' => [
                    'manfen' => $value->manfen,
                    'youxiu' => $value->youxiu,
                    'jige' => $value->jige,
                ],
            ];
        }

        if(count($data) > 0)
        {
            $data = \app\facade\Tools::sortArrByManyField($data, 'paixu', SORT_ASC);
        }

        return $data;
    }


    /**
     * 查询参加考试年级
     *
     * @return \think\Response
     */
    // 显示考试列表
    public function srcGrade($srcfrom)
    {
        // 初始化参数
        $src = array(
            'kaoshi_id' => '0'
            ,'page' => '1'
            ,'limit' => '10'
            ,'field' => 'ruxuenian'
            ,'order' => 'desc'
            ,'all' => false
        );
        $src = array_cover($srcfrom, $src);

        $njList = $this->where('kaoshi_id', $src['kaoshi_id'])
            ->group('ruxuenian')
            ->field("ruxuenian
                ,any_value(nianjiname) as nianjiname")
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        // 重新整理年级信息
        $data = array();
        foreach ($njList as $key => $value) {
            # code...
            $data[] = [
                'ruxuenian' => $value->ruxuenian,
                'nianjiname' => $value->nianjiname
            ];
        }

        return $data;
    }


    // 通过考试ID生成可以参加考试年级
    public function srcGradeByID($kaoshi_id)
    {
        $ks = new \app\kaoshi\model\Kaoshi;
        $time = $ks->where('id', $kaoshi_id)->field('bfdate, enddate')->find();
        $bj = \app\facade\Tools::gradeName('str', $time->getData('enddate'));
        return $bj;
    }

}
