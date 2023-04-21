<?php
declare (strict_types = 1);

namespace app\studentSearchChengji\controller;

// 引用学生查询基类
use \app\base\controller\StudentSearchBase;
// 引用与此控制器同名的数据模型
use \app\system\model\SystemBase as  sysbasemod;
// 引用学生数据模型类
use \app\studentSearchChengji\model\OneStudentChengji as STUCJ;
// 引用学生数据模型类
use \app\student\model\Student as STU;
// 引用加密类
use WhiteHat101\Crypt\APR1_MD5;

class Index extends StudentSearchBase
{
    // 学生查询页首页
    public function index()
    {
    	// 获取信息
        $sysbasemod = new \app\system\model\SystemBase;     # 关键字
        // 查询系统设置
        $list = $sysbasemod::sysInfo();     # 描述
        $list['version'] = config('shangma.version');   # 版本号
        // 设置要给模板赋值的信息

        $stu = new \app\student\model\Student;
        $stuInfo = $stu->where('id', session('user_id'))
                ->with([
                    'stuBanji' => function ($query) {
                        $query->field('id, paixu, ruxuenian')
                            ->append(['banjiTitle']);
                    }
                ])
                ->field('id, xingming, banji_id')
                ->find();
        $list['xingming'] = $stuInfo->xingming;
        $list['banjiTitle'] = $stuInfo->stuBanji->banjiTitle;
        $list['student_id'] = session('user_id');
        $subject = new \app\teach\model\Subject;

        // 查询学生成绩
        // 获取表单参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'kaoshi_id'
                ,'order' => 'desc'
                ,'student_id' => session('user_id')
                ,'category_id' => ''
                ,'xueqi_id' => ''
                ,'bfdate' => ''
                ,'enddate' => ''
                ,'searchval' => ''
            ], 'POST');

        // 获取学生成绩
        $stucj = new \app\tools\model\OneStudentChengji;
        $khList = $stucj->oldList($src);

        $sbj = new \app\teach\model\Subject;
        $list['subject'] = $sbj
            ->where('kaoshi', 1)
            ->column('title', 'lieming');

        $list['chengji'] = $khList;

        // halt($list->toArray());

        // 模板赋值
        $this->view->assign('list', $list);

        return $this->view->fetch();
    }



    // 学生查询页首页
    public function read($kaohao_id)
    {
        $kh = new \app\kaohao\model\Kaohao;
        $khInfo = $kh->where('id', $kaohao_id)
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

        return $this->view->fetch('kaohao@index/read');
    }


    // 修改密码
    public function editpassword()
    {
        // 设置页面标题
        $list['set'] = array(
            'webtitle' => '修改密码'
            ,'butname' => '修改'
            ,'formpost' => 'PUT'
            ,'url' => '/studentSearchChengji/index/updatepassword/' . session('user_id')
        );

        // 模板赋值
        $this->view->assign('list', $list);

        // 渲染模板
        return $this->view->fetch('admin@admin_info/edit_password');
    }


    // 保存新密码
    public function updatePassword($student_id)
    {
        // 获取表单数据
        $list = request()->post();

        // 验证表单数据
        $validate = new \app\admin\validate\SetPassword;
        $result = $validate->check($list);
        $msg = $validate->getError();

        // 如果验证不通过则停止保存
        if(!$result){
            return json(['msg' => $msg, 'val' => 0]);;
        }

        // 获取用户名
        $stu = new \app\student\model\Student;
        $serpassword = $stu::where('id', $student_id)->value('password');

        // 实例化加密类
        $md5 = new APR1_MD5();
        //验证密码
        $check = $md5->check($list['oldpassword'], $serpassword);

        if(!$check)
        {
            $data = ['msg' => '旧密码错误', 'val' => 0];
            return json($data);
        }

        // 更新密码
        $password = $md5->hash($list['newpassword']);
        $data = $stu::update(['id' => $student_id, 'password' => $password]);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '修改成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }
}
