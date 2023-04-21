<?php

use think\migration\Seeder;

class TongjiXingmu extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // 设置数据
        $rows = [
            // 管理员-班级-网页
            [
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12201
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12201
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-班级-下载
            ,[
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12202
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学校-网页
            ,[
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[

                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[

                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12203
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学校-下载
            ,[
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[

                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12204
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学生-网页
            ,[
                'title' => '得分率'
                ,'biaoshi' => 'defenlv'
                ,'tongji' => 0
                ,'paixu' => 2
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班排名'
                ,'biaoshi' => 'bpaixu'
                ,'tongji' => 0
                ,'paixu' => 3
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班超过'
                ,'biaoshi' => 'bweizhi'
                ,'tongji' => 0
                ,'paixu' => 4
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级排名'
                ,'biaoshi' => 'xpaixu'
                ,'tongji' => 0
                ,'paixu' => 5
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级超过'
                ,'biaoshi' => 'xweizhi'
                ,'tongji' => 0
                ,'paixu' => 6
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区排名'
                ,'biaoshi' => 'qpaixu'
                ,'tongji' => 0
                ,'paixu' => 7
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区超过'
                ,'biaoshi' => 'qweizhi'
                ,'tongji' => 0
                ,'paixu' => 8
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准分'
                ,'biaoshi'   =>  'biaozhunfen'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12205
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 教师-学科-网页
            ,[
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[

                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12207
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学生-下载右
            ,[
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12206
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学生-下载左
            ,[
                'title' => '得分率'
                ,'biaoshi' => 'defenlv'
                ,'tongji' => 0
                ,'paixu' => 2
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班排名'
                ,'biaoshi' => 'bpaixu'
                ,'tongji' => 0
                ,'paixu' => 3
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班超过'
                ,'biaoshi' => 'bweizhi'
                ,'tongji' => 0
                ,'paixu' => 4
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级排名'
                ,'biaoshi' => 'xpaixu'
                ,'tongji' => 0
                ,'paixu' => 5
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级超过'
                ,'biaoshi' => 'xweizhi'
                ,'tongji' => 0
                ,'paixu' => 6
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区排名'
                ,'biaoshi' => 'qpaixu'
                ,'tongji' => 0
                ,'paixu' => 7
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区超过'
                ,'biaoshi' => 'qweizhi'
                ,'tongji' => 0
                ,'paixu' => 8
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准分'
                ,'biaoshi'   =>  'biaozhunfen'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12210
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 教师-各学生-网页
            ,[
                'title' => '得分率'
                ,'biaoshi' => 'defenlv'
                ,'tongji' => 0
                ,'paixu' => 2
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班排名'
                ,'biaoshi' => 'bpaixu'
                ,'tongji' => 0
                ,'paixu' => 3
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班超过'
                ,'biaoshi' => 'bweizhi'
                ,'tongji' => 1
                ,'paixu' => 4
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级排名'
                ,'biaoshi' => 'xpaixu'
                ,'tongji' => 0
                ,'paixu' => 5
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级超过'
                ,'biaoshi' => 'xweizhi'
                ,'tongji' => 0
                ,'paixu' => 6
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区排名'
                ,'biaoshi' => 'qpaixu'
                ,'tongji' => 0
                ,'paixu' => 7
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区超过'
                ,'biaoshi' => 'qweizhi'
                ,'tongji' => 0
                ,'paixu' => 8
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准分'
                ,'biaoshi'   =>  'biaozhunfen'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12208
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学生成绩列表-成绩条(班级)
            ,[
                'title' => '学号数'
                ,'biaoshi' => 'stu_cnt'
                ,'tongji' => 0
                ,'paixu' => 1
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '成绩数'
                ,'biaoshi'   =>  'chengji_cnt'
                ,'tongji'        =>  1
                ,'paixu' => 2
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '总分'
                ,'biaoshi'   =>  'sum'
                ,'tongji'        =>  0
                ,'paixu' => 3
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '平均分'
                ,'biaoshi'   =>  'avg'
                ,'tongji'        =>  1
                ,'paixu' => 4
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '得分率'
                ,'biaoshi'   =>  'defenlv'
                ,'tongji'        =>  0
                ,'paixu' => 5
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准差'
                ,'biaoshi'   =>  'biaozhuncha'
                ,'tongji'        =>  0
                ,'paixu' => 6
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀人数'
                ,'biaoshi'   =>  'youxiu'
                ,'tongji'        =>  0
                ,'paixu' => 7
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '优秀率'
                ,'biaoshi'   =>  'youxiulv'
                ,'tongji'     =>  1
                ,'paixu' => 8
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格人数'
                ,'biaoshi'   =>  'jige'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '及格率'
                ,'biaoshi'   =>  'jigelv'
                ,'tongji'    =>  1
                ,'paixu' => 10
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最高分'
                ,'biaoshi'   =>  'max'
                ,'tongji'    =>  0
                ,'paixu' => 11
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '最低分'
                ,'biaoshi'   =>  'min'
                ,'tongji'    =>  0
                ,'paixu' => 12
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q1'
                ,'biaoshi'   =>  'q1'
                ,'tongji'        =>  0
                ,'paixu' => 13
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q2'
                ,'biaoshi'   =>  'q2'
                ,'tongji'    =>  0
                ,'paixu' => 14
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  'Q3'
                ,'biaoshi'   =>  'q3'
                ,'tongji'    =>  0
                ,'paixu' => 15
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '众数'
                ,'biaoshi'   =>  'zhongshu'
                ,'tongji'    =>  0
                ,'paixu' => 16
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '中位数'
                ,'biaoshi'   =>  'zhongweishu'
                ,'tongji'    =>  0
                ,'paixu' => 17
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '差生率'
                ,'biaoshi'   =>  'chashenglv'
                ,'tongji'    =>  0
                ,'paixu' => 18
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '参试率'
                ,'biaoshi'   =>  'canshilv'
                ,'tongji'    =>  0
                ,'paixu' => 19
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准分'
                ,'biaoshi'   =>  'biaozhunfen'
                ,'tongji'    =>  0
                ,'paixu' => 20
                ,'category_id' => 12212
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 管理员-学生成绩列表-成绩条(学生)
            ,[
                'title' => '得分率'
                ,'biaoshi' => 'defenlv'
                ,'tongji' => 0
                ,'paixu' => 2
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班排名'
                ,'biaoshi' => 'bpaixu'
                ,'tongji' => 0
                ,'paixu' => 3
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '班超过'
                ,'biaoshi' => 'bweizhi'
                ,'tongji' => 0
                ,'paixu' => 4
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级排名'
                ,'biaoshi' => 'xpaixu'
                ,'tongji' => 0
                ,'paixu' => 5
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '年级超过'
                ,'biaoshi' => 'xweizhi'
                ,'tongji' => 0
                ,'paixu' => 6
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区排名'
                ,'biaoshi' => 'qpaixu'
                ,'tongji' => 0
                ,'paixu' => 7
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title' => '区超过'
                ,'biaoshi' => 'qweizhi'
                ,'tongji' => 0
                ,'paixu' => 8
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]
            ,[
                'title'      =>  '标准分'
                ,'biaoshi'   =>  'biaozhunfen'
                ,'tongji'    =>  0
                ,'paixu' => 9
                ,'category_id' => 12211
                ,'create_time' => time()
                ,'update_time' => time()
            ]

            // 学生-各考试-网页
            // ,[
            //     'title' => '得分率'
            //     ,'biaoshi' => 'defenlv'
            //     ,'tongji' => 0
            //     ,'paixu' => 2
            //     ,'category_id' => 12209
            // ]
            // ,[
            //     'title' => '班排名'
            //     ,'biaoshi' => 'bpaixu'
            //     ,'tongji' => 0
            //     ,'paixu' => 3
            //     ,'category_id' => 12209
            // ]
            // ,[
            //     'title' => '班超过'
            //     ,'biaoshi' => 'bweizhi'
            //     ,'tongji' => 0
            //     ,'paixu' => 4
            //     ,'category_id' => 12209
            // ]
            // ,[
            //     'title' => '年级排名'
            //     ,'biaoshi' => 'xpaixu'
            //     ,'tongji' => 0
            //     ,'paixu' => 5
            //     ,'category_id' => 12209
            // ]
            // ,[
            //     'title' => '年级超过'
            //     ,'biaoshi' => 'xweizhi'
            //     ,'tongji' => 0
            //     ,'paixu' => 6
            //     ,'category_id' => 12209
            // ]
            // ,[
            //     'title' => '区排名'
            //     ,'biaoshi' => 'qpaixu'
            //     ,'tongji' => 0
            //     ,'paixu' => 7
            //     ,'category_id' => 12209
            // ]
            // ,[
            //     'title' => '区超过'
            //     ,'biaoshi' => 'qweizhi'
            //     ,'tongji' => 0
            //     ,'paixu' => 8
            //     ,'category_id' => 12209
            // ]
    ];

        $this->execute('DELETE FROM cj_tongji_xiangmu where id>0');

        // 保存数据
        $this->table('tongji_xiangmu')->insert($rows)->save();
    }
}
