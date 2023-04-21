<?php
namespace app\admin\controller;

// 引用控制器基类
use app\base\controller\AdminBase;

use app\admin\model\Admin as ad;

class Tongbu extends AdminBase
{
    // 统计成绩
    public function index()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '同步数据'
            ,'butname' => '一键同步'
            ,'formpost' => 'POST'
            ,'url' => '/tongbu/null'
            ,'kaoshi_id' => 0
        );

        $list['tjxm'] = [
            [
                'title' => '教师信息（必须最先同步，此项同步完成后再进行其它项同步）'
                ,'url' => '/admin/tongbu/teacher'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '班主任'
                ,'url' => '/admin/tongbu/bzr'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '成绩'
                ,'url' => '/admin/tongbu/chengji'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '单位荣誉'
                ,'url' => '/admin/tongbu/dwry'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '文件'
                ,'url' => '/admin/tongbu/file'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '教师荣誉'
                ,'url' => '/admin/tongbu/jsry'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '考试'
                ,'url' => '/admin/tongbu/kaoshi'
                ,'checked' => ' checked'
            ]
            ,[
                'title' => '课题'
                ,'url' => '/admin/tongbu/ktcy'
                ,'checked' => ''
            ]
            ,[
                'title' => '班级'
                ,'url' => '/admin/tongbu/tjbj'
                ,'checked' => ''
            ]
            ,[
                'title' => '补充考试信息'
                ,'url' => '/admin/tongbu/bcks'
                ,'checked' => ' checked'
            ]
        ];

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染
        return $this->view->fetch('index/tongji');
    }

    // 将老师信息合并到admin中
    public function teacher()
    {
        $ad = new \app\admin\model\Admin;
        $tc = new \app\teacher\model\Teacher;
        $tclist = $tc::withTrashed()->select();
        $i = 0;
        foreach ($tclist as $key => $value) {
            $temp = $ad::withTrashed()
                ->where('teacher_id', $value->id)
                ->find();
            if($temp)
            {
                $arr['id'] = $temp->id;
                $arr['xingming'] = $value->xingming;
                $arr['sex'] = $value->getData("sex");
                $arr['shengri'] = $value->shengri;
                $arr['password'] = $value->password;
                $arr['teacher_id'] = $value->id;
                $arr['school_id'] = $value->danwei_id;
                $arr['phone'] = $value->phone;
                $arr['worktime'] = $value->worktime;
                $arr['zhiwu_id'] = $value->zhiwu_id;
                $arr['zhicheng_id'] = $value->zhicheng_id;
                $arr['biye'] = $value->biye;
                $arr['zhuanye'] = $value->zhuanye;
                $arr['xueli_id'] = $value->xueli_id;
                $arr['subject_id'] = $value->subject_id;
                $arr['quanpin'] = $value->quanpin;
                $arr['shoupin'] = $value->shoupin;
                $arr['tuixiu'] = $value->getData("tuixiu");
                $arr['denglucishu'] = $value->denglucishu + $temp->denglucishu;
                if($temp->this_time < $value->this_time)
                {
                    $arr['lastip'] = $value->lastip;
                    $arr['ip'] = $value->ip;
                    $arr['lasttime'] = strtotime($value->lasttime);
                    $arr['thistime'] = strtotime($value->thistime);
                }
                $temp->status = $value->status;
                if($temp->create_time > $value->create_time)
                {
                    $arr['create_time'] = strtotime($value->create_time);
                }
                if($temp->delete_time < $value->delete_time)
                {
                    $arr['delete_time'] = $value->delete_time;
                }
                $arr['beizhu'] = $value->beizhu;
                $data = $ad::update($arr);
            } else {
                $data = $value->toArray();
                $data['teacher_id'] = $value->id;
                unset($data['id']);
                $data['sex'] = $value->getData('sex');
                $data['shengri'] = $value->shengri;
                $data['username'] = $value->phone;
                $data['school_id'] = $value->danwei_id;
                unset($data['danwei_id']);
                $data['delete_time'] = $value->delete_time;
                $data['worktime'] = $value->worktime;
                $data['tuixiu'] = $value->getData('tuixiu');
                $data['create_time'] = strtotime($value->create_time);
                $data['delete_time'] = $value->delete_time;
                unset($data['update_time']);
                $data = $ad->create($data);
            }
            $i = $i+1;
        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '教师信息同步成功,共' . $i . '条记录', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

       return json($data);
    }


    // 班主任
    public function bzr()
    {
        $bzr = new \app\teach\model\BanZhuRen;
        $bzrList = $bzr::withTrashed()->field('id, teacher_id')->select();
        $arr = array();
        foreach ($bzrList as $key => $value) {
            $arr[] = [
                'id' => $value->id
                ,'teacher_id' => ad::newId($value->teacher_id)
            ];
        }
        if (count($arr) > 0) {
            $data = $bzr->saveAll($arr);
        } else {
            $data = true;
        }
        
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '班主任同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        return json($data);
    }

    // 成绩录入人
    public function chengji()
    {
        $chengji = new \app\chengji\model\Chengji;
        $chengjiList = $chengji->where('user_group', '<>', 'admin')->field('id, user_id')->select();
        $arr = array();
        foreach ($chengjiList as $key => $value) {
            $arr[] = [
                'id' => $value->id
                ,'user_id' => ad::newId($value->user_id)
            ];
        }
        if (count($arr) > 0) {
            $data = $chengji->saveAll($arr);
        } else {
            $data = true;
        }
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '成绩同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 单位荣誉参与
    public function dwry()
    {
        $dwry = new \app\rongyu\model\DwRongyuCanyu;
        $dwryList = $dwry::withTrashed()->field('id, teacher_id')->select();
        $arr = array();
        foreach ($dwryList as $key => $value) {
            $arr[] = [
                'id' => $value->id
                ,'teacher_id' => ad::newId($value->teacher_id)
            ];
        }
        if (count($arr) > 0) {
            $data = $dwry->saveAll($arr);
        } else {
            $data = true;
        }
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '单位荣誉同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 文件
    public function file()
    {
        $file = new \app\system\model\Fields;
        $fileList = $file::withTrashed()->where('user_group', '<>', 'admin')->field('id, user_id')->select();
        $arr = array();
        foreach ($fileList as $key => $value) {
            $arr[] = [
                'id' => $value->id
                ,'user_id' => ad::newId($value->user_id)
            ];
        }
        if (count($arr) > 0) {
            $data = $file->saveAll($arr);
        } else {
            $data = true;
        }
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '文件同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 教师荣誉参与
    public function jsry()
    {
        $jsry = new \app\rongyu\model\JsRongyuCanyu;
        $jsryList = $jsry::withTrashed()->field('id, teacher_id')->select();
        $arr = array();
        foreach ($jsryList as $key => $value) {
            $arr[] = [
                'id' => $value->id
                ,'teacher_id' => ad::newId($value->teacher_id)
            ];
        }
        if (count($arr) > 0) {
            $data = $jsry->saveAll($arr);
        } else {
            $data = true;
        }
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '教师同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 考试
    public function kaoshi()
    {
        $kaoshi = new \app\kaoshi\model\Kaoshi;
        $kaoshiList = $kaoshi::withTrashed()->select();
        $data = false;
        if(isset($kaoshiList[0]['user_id']))
        {
            $arr = array();
            foreach ($kaoshiList as $key => $value) {
                if($value->user_id > 0){
                   $arr[] = [
                        'id' => $value->id
                        ,'user_id' => ad::newId($value->teacher_id)
                    ];
                }
            }
            if (count($arr) > 0) {
                $data = $kaoshi->saveAll($arr);
            } else {
                $data = true;
            }
        } else {
            $data = ['msg' => '没有相关考试信息', 'val' => 1];
        }

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '考试同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 课题参与
    public function ktcy()
    {
        $ktcy = new \app\keti\model\KetiCanyu;
        $ktcyList = $ktcy::withTrashed()->field('id, teacher_id')->select();
        $arr = array();
        foreach ($ktcyList as $key => $value) {
             $arr[] = [
                'id' => $value->id
                ,'oldid' => $value->teacher_id
                ,'teacher_id' => ad::newId($value->teacher_id)
            ];
        }
        if (count($arr) > 0) {
            $data = $ktcy->saveAll($arr);
        } else {
            $data = true;
        }
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '课题同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 统计班级
    public function tjbj()
    {
        $tjbj = new \app\chengji\model\TongjiBj;
        $tjbjList = $tjbj::withTrashed()->field('id, teacher_id')->select();
        $arr = array();
        foreach ($tjbjList as $key => $value) {
            $arr[] = [
                'id' => $value->id
                ,'teacher_id' => ad::newId($value->teacher_id)
            ];
        }
         if (count($arr) > 0) {
            $data = $tjbj->saveAll($arr);
        } else {
            $data = true;
        }
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '统计班级同步完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }


    // 添加默认考试信息
    public function kaoshiMoren()
    {
        $ks = new \app\kaoshi\model\Kaoshi;
        $ids = $ks::withTrashed()
            ->column(['id']);
        $list = array();
        foreach ($ids as $key => $value) {
            $list[] = [
                'id' => $value
                ,'fanwei_id' => 12401
                ,'user_id' => 1
            ];
        }
        $data = $ks->saveAll($list);
        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '考试信息补充完成', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];
        return json($data);
    }
}
