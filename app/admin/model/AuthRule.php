<?php

namespace app\admin\model;

// 引用用户数据模型
use app\BaseModel;

class AuthRule extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'name' => 'varchar'
        ,'title' => 'varchar'
        ,'status' => 'tinyint'
        ,'condition' => 'varchar'
        ,'paixu' => 'int'
        ,'ismenu' => 'tinyint'
        ,'font' => 'varchar'
        ,'url' => 'varchar'
        ,'pid' => 'int'
        ,'type' => 'tinyint'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
    ];


    // 查询所有角色
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'searchval' => ''
            ,'status' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        // 查询数据
        $data = $this
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('title|name', 'like', '%' . $src['searchval'] . '%');
                })
            ->when(strlen($src['status']) > 0, function($query) use($src){
                    $query->where('status', $src['status']);
                })
            ->with([
                'authPid' => function($query){
                    $query->field('id, title');
                }
            ])
            ->field('id, name, title, condition, paixu, ismenu, url, pid, type, status')
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->select();

        return $data;
    }


    // 查询菜单
    public static function menu()
    {
        $user_id = session('user_id');
        if($user_id == 1 || $user_id ==2)
        {
            $auth = array();
        }else{
            $admin = new \app\admin\model\Admin;
            $auth = $admin->srcAuth($user_id);
        }

        $data = self::where('pid', 0)
            ->where('status&ismenu', 1)
            ->when(count($auth) > 0, function($query) use($auth){
                $query->where('id', 'in', $auth);
            })
            ->field('id, title, font')
            ->with([
                'authCid' => function($query){
                    $query->order(['paixu'])
                        ->where('status&ismenu', 1)
                        ->field('id, pid, title, url');
                }
            ])
            ->order(['paixu'])
            ->select();

        return $data;
    }


    // 递归权限类别
    public function digui($arrAll = array(), $arrSelected = array(), $pid = 0)
    {
        // 声明子权限
        $child = array();
        count($arrSelected) == 0 ? $e = false : $e = true;
        // 循环所有权限
        foreach ($arrAll as $key => $value) {
            # 获取当前子权限
            if($value['pid'] == $pid)
            {
                # 判断当前权限是否被选中
                if($e == true)
                {
                    $selk = array_search($value['id'], $arrSelected);
                    if(false === $selk)
                    {
                        $value['select'] = false;
                    } else {
                        $value['select'] = true;
                        unset($arrSelected[$selk]);
                    }
                }else{
                    $value['select'] = false;
                }

                $child[$value['id']] = $value;
                unset($arrAll[$key]);
                $child[$value['id']]['child'] = $this->digui($arrAll, $arrSelected, $value['id']);
            }
        }
        return $child;
    }


    // 父级菜单数据模型关联
    public function authPid()
    {
        return $this->belongsTo(AuthRule::class, 'pid', 'id');
    }


    // 子菜单数据模型关联
    public function authCid()
    {
        return $this->hasMany(AuthRule::class, 'pid', 'id');
    }
}
