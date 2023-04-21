<?php
namespace app\teach\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class JiaoyanZuzhang extends Model
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'teacher_id' => 'int'
        ,'jiaoyanzu_id' => 'int'
        ,'bfdate' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];


    // 教师关联
    public function glTeacher()
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'teacher_id', 'id');
    }


    // 教研组关联
    public function glJiaoyanzu()
    {
        return $this->belongsTo(\app\teach\model\Jiaoyanzu::class, 'jiaoyanzu_id', 'id');
    }

    // 根据条件查询教研组
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'teacher_id' => ''
            ,'jiaoyanzu_id' => '0'
            ,'searchval' => ''
            ,'category_id' => ''
            ,'status' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src) ;

        // 查询数据
        $data = $this
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
            	$query
            		->where('teacher_id', 'in', function($q) use($src) {
            			$q->name('admin')
            				->where('xingming', 'like', '%' . $src['searchval'] . '%')
            				->field('id');
            		});
                })
            ->where('jiaoyanzu_id', $src['jiaoyanzu_id'])
            ->when(strlen($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', $src['category_id']);
                })
            ->when(strlen($src['status']) > 0, function($query) use($src){
                    $query->where('status', $src['status']);
                })
            ->with(
                [
                    'glTeacher'=>function($query){
                        $query->field('id, xingming');
                    },
                    'glJiaoyanzu'=>function($query){
                        $query->field('id, title');
                    },
                ]
            )
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        return $data;
    }


    // 查询教师担任组长情况
    public function srcTeacherNow($admin_id)
    {
        $teacher = $this->where('teacher_id', $admin_id)
            ->whereTime('bfdate', '<=', time())
            ->order(['bfdate'=>'desc'])
            ->field('jiaoyanzu_id
                ,any_value(teacher_id) as teacher_id
                ,any_value(bfdate) as bfdate')
            ->group('jiaoyanzu_id')
            ->with([
                'glJiaoyanzu' => function ($query) {
                    $query->field('id, title')
                        ->where('status', 1)
                        ->append(['zuzhang']);
                }
            ])
            ->select();

        // 循环写入班级ID
        $jyz_ids = array();
        foreach ($teacher as $key => $value) {
            if (isset($value->glJiaoyanzu) && $value->glJiaoyanzu->zuzhang['id'] == $admin_id) {
                $jyz_ids[] = $value->jiaoyanzu_id;
            }
        }

        return $jyz_ids;
    }


    // 查询组长权限
    public function zzAuth()
    {
        $id = session('user_id');
        $banji_id = array();
        $zhList = $this->srcTeacherNow($id);
        $zz = new \app\teach\model\Jiaoyanzu;
        foreach ($zhList as $key => $value) {
            $zzInfo = $zz->oneInfo($value);
            $banji_id = array_merge($banji_id, $zzInfo->banjiId);
        }
        return $banji_id;
    }


    // 接任时间获取器
    public function getBfdateAttr($value)
    {
        return date('Y-m-d',$value);
    }


    // 接任时间修改器
    public function setBfdateAttr($value)
    {
        return strtotime($value);
    }
}
