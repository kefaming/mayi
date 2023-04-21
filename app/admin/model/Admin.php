<?php

namespace app\admin\model;

// 引用用户数据模型
use app\BaseModel;

class Admin extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'xingming' => 'varchar'
        ,'sex' => 'tinyint'
        ,'shengri' => 'int'
        ,'username' => 'varchar'
        ,'password' => 'varchar'
        ,'teacher_id' => 'int'
        ,'school_id' => 'int'
        ,'phone' => 'varchar'
        ,'id' => 'int'
        ,'worktime' => 'int'
        ,'zhiwu_id' => 'int'
        ,'zhicheng_id' => 'int'
        ,'biye' => 'varchar'
        ,'zhuanye' => 'varchar'
        ,'xueli_id' => 'int'
        ,'subject_id' => 'int'
        ,'quanpin' => 'varchar'
        ,'shoupin' => 'varchar'
        ,'tuixiu' => 'tinyint'
        ,'denglucishu' => 'int'
        ,'lastip' => 'varchar'
        ,'ip' => 'varchar'
        ,'lasttime' => 'int'
        ,'thistime' => 'int'
        ,'status' => 'tinyint'
        ,'guoqi' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'beizhu' => 'varchar'
    ];


    // 查询按条件查用户
    public function search($srcfrom)
    {
        // 整理变量
        $src = [
            'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);

        // 查询数据
        $data = $this
            ->where('id', '>', 2)
            ->where('id', '<>', session('user_id'))
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                    $query->where('xingming|username|quanpin|shoupin', 'like', '%' . $src['searchval'] . '%');
                })
            ->with([
                'adSchool' => function ($q) {
                    $q->field('id, jiancheng');
                }
                ,'glGroup'
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->hidden([
                'password'
                ,'create_time'
                ,'update_time'
                ,'delete_time'
            ])
            ->append(['groupnames'])
            ->select();

        return $data;
    }


    // 查询用户权限
    public function srcAuth($user_id)
    {
        // 查询权限
        $group = self::where('id', $user_id)
            ->with(['glGroup'])
            ->where('status', 1)
            ->find();

        $rules = array();
        foreach ($group->glGroup as $key => $value) {
            // code...
            $rules[] = $value->rules;
        }
        // 整理权限
        $rules = array_unique(explode(',', implode(',', $rules)));

        return $rules;
    }


    // 查询管理员资料
    public function searchOne($id)
    {
        $adminInfo = $this
            ->where('id', $id)
            ->with([
                'adSchool' => function($query){
                    $query->field('id, title');
                },
                'adZhiwu' => function($query){
                    $query->field('id, title');
                },
                'adZhicheng' => function($query){
                    $query->field('id, title');
                },
                'adXueli' => function($query){
                    $query->field('id, title');
                },
                'glGroup'
            ])
            ->append(['groupnames'])
            ->hidden(['password', 'delete_time'])
            ->find();

        return $adminInfo;
    }


    // 根据姓名、首拼查询教师
    public function strSrcTeachers($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'school_id' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        $src = array_cover($srcfrom, $src);
        $src['searchval'] = trim($src['searchval']);
        if ($src['searchval'] == "" && $src['school_id'] == "") {
            return array();
        }
        // 如果有数据则查询教师信息
        $list = self::field('id, xingming, school_id, shengri, sex')
            ->when(strlen($src['school_id']) > 0, function ($query) use($src) {
                $query->where('school_id', $src['school_id']);
            })
            ->when(strlen($src['searchval']) <= 0, function ($query) use($src) {
                $query->where('id', '<=', 0);
            })
            ->when(strlen($src['searchval']) > 0, function ($query) use($src) {
                $query
                    ->where('xingming|shoupin', 'like', '%' . $src['searchval'] . '%');
            })
            ->with(
                [
                    'adSchool' => function($query){
                        $query->field('id, jiancheng');
                    }
                ]
            )
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->append(['age'])
            ->select();
        return $list;
    }


    // 表格导入教师信息
    public function createAll($arr, $school_id)
    {
        $pinyin = new \Overtrue\Pinyin\Pinyin;

        // 整理表格数据
        array_splice($arr, 0, 4); # 删除标题行

        // 获取帐号禁用时间
        $jinyong = \app\facade\Tools::mima_guoqi();
        // 组合需要保存的数据
        $teacherlist = array();
        $validate = new \app\admin\validate\Admin;
        $err = ['导入失败原因如下：'];
        $cnt = 0;
        $i = 0;
        foreach ($arr as $key => $value) {
            array_map("trim", $value);      # 去除表格中的空格
            $temp = array_filter($value,function($q){       # 判断是否是行，如果是空行则删除
                return $q != "";
            });
            if (count($temp) == 1 && $temp[0] != "") {
                continue;
            }
            $cnt++;
            $teacherInfo[$i]['xingming'] = trim($value[1]);
            $teacherInfo[$i]['username'] = trim(strtolower($value[2]));
            $teacherInfo[$i]['sex'] = $this->cutStr($value[3]);
            $teacherInfo[$i]['shengri'] = $value[4];
            $teacherInfo[$i]['phone'] = str_replace(' ', '', $value[5]);
            $teacherInfo[$i]['worktime'] = $value[6];
            $teacherInfo[$i]['zhiwu_id'] = $this->cutStr($value[7]);
            $teacherInfo[$i]['zhicheng_id'] = $this->cutStr($value[8]);
            $teacherInfo[$i]['school_id'] = $school_id;
            $teacherInfo[$i]['biye'] = trim($value[9]);
            $teacherInfo[$i]['zhuanye'] = trim($value[10]);
            $teacherInfo[$i]['xueli_id'] = $this->cutStr($value[11]);
            $quanpin = $pinyin->sentence($value[1]);
            $jianpin = $pinyin->abbr($value[1]);
            $teacherInfo[$i]['quanpin'] = trim(strtolower(str_replace(' ', '', $quanpin)));
            $teacherInfo[$i]['shoupin'] = trim(strtolower($jianpin));
            $teacherInfo[$i]['beizhu'] = $value[12];
            $teacherInfo[$i]['lasttime'] = time();
            $teacherInfo[$i]['thistime'] = time();
            $teacherInfo[$i]['guoqi'] = $jinyong;
            // 验证数据
            $result = $validate->scene('admincreateall')->check($teacherInfo[$i]);
            $msg = $validate->getError();
            if (!$result) {
                $err[] = '第' . $value[0] . '行,' . $msg;
                unset($teacherInfo[$i]);
            }
            $i ++;
        }

        // 启动事务
        \think\facade\Db::startTrans();
        try {
            $data = $this->saveAll($teacherInfo);
            $success = $data->count();
            \think\facade\Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            \think\facade\Db::rollback();
            $success = 0;
            $err[] = $e->getMessage();
        }

        // 整理返回信息
        $data['success'] = '共有'. $cnt .'条记录，成功导入' . $success . '条记录。';
        if($cnt == $success) {
            $data['err'] = array();
        } else {
            $data['err'] = $err;
        }

        return $data;
    }


    // 获取密码
    public function password($username)
    {
        // 查询数据
        $pasW = $this
            ->where('username', $username)
            ->value('password');

        // 返回数据
        return $pasW;
    }


    // 查询用户名是否唯一
    public function onlyUsername($srcfrom)
    {
        // 初始值
        $src = [
            'searchval' => ''
            ,'id' => ''
        ];
        $src = array_cover($srcfrom, $src);

        $list = $this::withTrashed()
            ->where('username', $src['searchval'])
            ->find();
        $data = ['msg' => '用户名已经存在！', 'val' => 0];

        if($list)
        {
            if($src['id'] == $list->id){
                $data = ['msg' => '', 'val' => 1];
            }
        }else{
           $data = ['msg' => '', 'val' => 1];
        }
    }

    // 查询用户名是否唯一
    public function onlyPhone($srcfrom)
    {
        // 初始值
        $src = [
            'searchval' => ''
            ,'id' => ''
        ];
        $src = array_cover($srcfrom, $src);

        $list = $this::withTrashed()
            ->where('phone', $src['searchval'])
            ->find();
        $data = ['msg' => '手机号已经存在！', 'val' => 0];

        if($list)
        {
            if($src['id'] == $list->id){
                $data = ['msg' => '', 'val' => 1];
            }
        }else{
           $data = ['msg' => '', 'val' => 1];
        }
    }


    // 关联用户组
    public function glGroup()
    {
        return $this->belongsToMany(AuthGroup::class, 'AuthGroupAccess', 'group_id', 'uid');
    }


    // 单位关联模型
    public function adSchool()
    {
        return $this->belongsTo(\app\system\model\School::class, 'school_id', 'id');
    }


    // 职务关联模型
    public function adZhiwu()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'zhiwu_id', 'id');
    }


    // 职称关联模型
    public function adZhicheng()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'zhicheng_id', 'id');
    }


    // 学历关联模型
    public function adXueli()
    {
        return $this->belongsTo(\app\system\model\Category::class, 'xueli_id', 'id');
    }


    // 学科关联模型
    public function adSubject()
    {
        return $this->belongsTo(\app\teach\model\Subject::class, 'subject_id', 'id');
    }


    // 生日修改器
    public function setShengriAttr($value)
    {
        strlen($value) >0 ? $value = strtotime($value) : $value = '';
        return $value;
    }


    // 生日获取器
    public function getShengriAttr($value)
    {
        return date('Y-m-d', $value);
    }


    // 性别获取器
    public function getSexAttr($value)
    {
        $sex = [
            '0' => '女'
            ,'1' => '男'
            ,'2' => '保密'
        ];

        $str = '';
        if(isset($sex[$value]))
        {
            $str = $sex[$value];
        }else{
            $str = '未知';
        }

        return $str;
    }


    // 本次登录时间取器
    public function getThistimeAttr($value)
    {
        return date('Y年m月d日 H:i:s', $value);
    }


    // 最后一次登录时间取器
    public function getLasttimeAttr($value)
    {
        return date('Y年m月d日 H:i:s', $value);
    }


    // 参加工作时间修改器
    public function getWorktimeAttr($value)
    {
        return date('Y-m-d', $value);
    }


    // 生日修改器
    public function setWorktimeAttr($value)
    {
        strlen($value) >0 ? $value = strtotime($value) : $value = '';
        return $value;
    }


    // 初始密码过期时间获取器
    public function getGuoqiAttr($value)
    {
        return date('Y年m月d日 H:i:s', $value);
    }


    // 获取角色名称
    public function getGroupnamesAttr($value)
    {
        // 如果用户ID为1或2，则为超级管理员
        if($this->getAttr('id') == 1 || $this->getAttr('id') == 2)
        {
            return '超级管理员';
        }

        // 查询用户拥有的权限
        $group = $this->glGroup()->where('status', 1)->column(['title']);
        $groupname = implode('、', $group);

        // 返回角色名
        return $groupname;
    }


    // 退休获取器
    public function getTuixiuAttr($value)
    {
        $sex = array('0' => '在职', '1' => '退休');
        $str = '';
        if(isset($sex[$value]))
        {
            $str = $sex[$value];
        }else{
            $str = '未知';
        }
        return $str;
    }


    // 年龄获取器
    public function getAgeAttr()
    {
        return \app\facade\Tools::fBirth($this->getdata('shengri'), 2);
    }


    // 工龄获取器
    public function getGonglingAttr()
    {
        return \app\facade\Tools::fBirth($this->getdata('worktime'), 2);
    }


    // 分割表格上传数据
    private function cutStr($value)
    {
        $value = explode('|', $value);
        $str = '';
        if(isset($value[1]) && $value >0 )
        {
            $str = $value[1];
        }

        return $str;
    }

    static function newId($oldId)
    {
        $id = self::withTrashed()->where('teacher_id', $oldId)->value('id');
        return $id;
    }


    // 职务权限
    public function zwAuth()
    {
        $adInfo = $this->searchOne(session('user_id'));
        $banji_id = array();
        // 获取职务权限
        $zhiwu_array = array(10701, 10703, 10705);

        if (in_array($adInfo->zhiwu_id, $zhiwu_array)) {
            $bj = new \app\teach\model\Banji;
            $bjList = $bj->where('school_id', $adInfo->school_id)
                ->where('status', 1)
                ->column('id');
            $banji_id = array_merge($banji_id, $bjList);  # 这个地方为什么要合并？
        }

        return $banji_id;
    }


    public function myQuanxian($srcfrom)
    {
        // 获取合并变量
        $src = [
            'guanliyuan' => true
            ,'zhiwu' => true
            ,'zuzhang' => true
            ,'banzhuren' => true
            ,'renke' => true
            ,'time' => 0
        ];
        $src = array_cover($srcfrom, $src);

        // 初始化变量
        $data = [
            'check' => true
            ,'banji_id' => array()
        ];
        $id = session('user_id');   # 获取当前用户ID

        if(array_sum($src) == 0)
        {
            $data['check'] = false;
            return $data;
        }

        // 超级管理员验证
        if ($src['guanliyuan'] === true) {
            if($id == 1 || $id == 2)
            {
                $data['check'] = false;
                return $data;
            }
        }

        // 职务权限
        if($src['zhiwu'] === true) {
            $banji_id = $this->zwAuth();
            $data['banji_id'] = array_merge($data['banji_id'], $banji_id);
        }

        // 教研组长
        if ($src['zuzhang'] === true) {
            $auth = new \app\teach\model\JiaoyanZuzhang;
            $banji_id = $auth->zzAuth();
            $data['banji_id'] = array_merge($data['banji_id'], $banji_id);
        }

        // 获取班主任班级权限
        if ($src['banzhuren'] === true) {
            $auth = new \app\teach\model\BanZhuRen;
            $banji_id = $auth->bzrAuth();
            $data['banji_id'] = array_merge($data['banji_id'], $banji_id);
        }

        // 获取任课教师权限
        if ($src['renke'] === true) {
            $auth = new \app\teach\model\FenGong;
            $banji_id = $auth->teacherFengong(session('user_id'), $src['time']);
            $banji_id = array_keys($banji_id);
            $data['banji_id'] = array_merge($data['banji_id'], $banji_id);
        }

        // 去重
        $data['banji_id'] = array_unique($data['banji_id']);

        return $data;
    }


}
