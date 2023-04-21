<?php

use think\migration\Seeder;

class Category extends Seeder
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
        // 初始化类别
        $rows= [
            // 一级类别
            ['id' => 101
                ,'title' => '单位性质'
                ,'p_id' => 0
                ,'paixu'  => 1
                ,'isupdate' => 0
            ],
                // 单位性质
                ['id' => 10101,'title' => '幼儿园','p_id' => 101,'paixu' => 1,'isupdate' => 0],
                ['id' => 10102,'title' => '小学','p_id' => 101,'paixu' => 2,'isupdate' => 0],
                ['id' => 10103,'title' => '九年一贯','p_id' => 101,'paixu' => 3,'isupdate' => 0],
                ['id' => 10104,'title' => '初中','p_id' => 101,'paixu' => 4,'isupdate' => 0],
                ['id' => 10105,'title' => '高中','p_id' => 101,'paixu' => 5,'isupdate' => 0],
                ['id' => 10106,'title' => '中等职业技术学校','p_id' => 101,'paixu' => 6,'isupdate' => 0],
                ['id' => 10107,'title' => '科研机构(教师进修学校)','p_id' => 101,'paixu' => 7,'isupdate' => 0],
                ['id' => 10108,'title' => '教育行政部门','p_id' => 101,'paixu' => 8,'isupdate' => 0],
                ['id' => 10109,'title' => '其他教育机构','p_id' => 101,'paixu' => 9,'isupdate' => 0],

            ['id' => 102
                ,'title' => '单位级别'
                ,'p_id' => 0
                ,'paixu'  => 2
                ,'isupdate' => 0
            ],
                // 单位级别
                ['id' => 10201,'title' => '班级','p_id' => 102,'paixu' => 1,'status' => 0,'isupdate' => 0],
                ['id' => 10202,'title' => '教研组','p_id' => 102,'paixu' => 2,'isupdate' => 0],
                ['id' => 10203,'title' => '校级','p_id' => 102,'paixu' => 3,'isupdate' => 0],
                ['id' => 10204,'title' => '区级','p_id' => 102,'paixu' => 4,'isupdate' => 0],
                ['id' => 10205,'title' => '市级','p_id' => 102,'paixu' => 5,'isupdate' => 0],
                ['id' => 10206,'title' => '省级','p_id' => 102,'paixu' => 6,'isupdate' => 0],
                ['id' => 10207,'title' => '部级','p_id' => 102,'paixu' => 7,'isupdate' => 0],
                ['id' => 10208,'title' => '其它级','p_id' => 102,'paixu' => 8,'isupdate' => 0],
            ['id' => 103
                ,'title' => '大学段'
                ,'p_id' => 0
                ,'paixu'  => 3
                ,'isupdate' => 0
            ],
                // 大学段
                ['id' => 10301,'title' => '幼儿园','p_id' => 103,'paixu' => 1,'isupdate' => 0],
                ['id' => 10302,'title' => '小学','p_id' => 103,'paixu' => 2,'isupdate' => 0],
                ['id' => 10303,'title' => '中小学','p_id' => 103,'paixu' => 3,'isupdate' => 0],
                ['id' => 10304,'title' => '初中','p_id' => 103,'paixu' => 4,'isupdate' => 0],
                ['id' => 10305,'title' => '高中','p_id' => 103,'paixu' => 5,'isupdate' => 0],
                ['id' => 10306,'title' => '大学','p_id' => 103,'paixu' => 6,'isupdate' => 0],
                ['id' => 10307,'title' => '其他学段','p_id' => 103,'paixu' => 7,'isupdate' => 0],
            ['id' => 104
                ,'title' => '小学段'
                ,'p_id' => 0
                ,'paixu'  => 4
                ,'isupdate' => 0
            ],
                // 大学段
                ['id' => 10401,'title' => '低段','p_id' => 104,'paixu' => 1,'isupdate' => 0],
                ['id' => 10402,'title' => '中段','p_id' => 104,'paixu' => 2,'isupdate' => 0],
                ['id' => 10403,'title' => '高段','p_id' => 104,'paixu' => 3,'isupdate' => 0],
                ['id' => 10404,'title' => '其他学段','p_id' => 104,'paixu' => 4,'isupdate' => 0],
            ['id' => 105
                ,'title' => '学历'
                ,'p_id' => 0
                ,'paixu' => 5
                ,'isupdate' => 0
            ],
                // 学历
                ['id' => 10501,'title' => '高中/中专','p_id' => 105,'paixu' => 1,'isupdate' => 0],
                ['id' => 10502,'title' => '专科','p_id' => 105,'paixu' => 2,'isupdate' => 0],
                ['id' => 10503,'title' => '本科','p_id' => 105,'paixu' => 3,'isupdate' => 0],
                ['id' => 10504,'title' => '硕士研究生','p_id' => 105,'paixu' => 4,'isupdate' => 0],
                ['id' => 10505,'title' => '博士研究生','p_id' => 105,'paixu' => 5,'isupdate' => 0],
                ['id' => 10506,'title' => '其他学历','p_id' => 105,'paixu' => 6,'isupdate' => 0],
            ['id' => 106
                ,'title' => '职称'
                ,'p_id' => 0
                ,'paixu' => 6
                ,'isupdate' => 0
            ],
                // 职称
                ['id' => 10601,'title' => '正高级','p_id' => 106,'paixu' => 1,'isupdate' => 0],
                ['id' => 10602,'title' => '高级','p_id' => 106,'paixu' => 2,'isupdate' => 0],
                ['id' => 10603,'title' => '一级','p_id' => 106,'paixu' => 3,'isupdate' => 0],
                ['id' => 10604,'title' => '二级','p_id' => 106,'paixu' => 4,'isupdate' => 0],
                ['id' => 10605,'title' => '三级','p_id' => 106,'paixu' => 5,'isupdate' => 0],
                ['id' => 10606,'title' => '其他','p_id' => 106,'paixu' => 6,'isupdate' => 0],
            ['id' => 107
                ,'title' => '职务'
                ,'p_id' => 0
                ,'paixu' => 7
                ,'isupdate' => 0
            ],
                // 职务
                ['id' => 10701,'title' => '校长、书记','p_id' => 107,'paixu' => 1,'isupdate' => 0],
                ['id' => 10702,'title' => '副书记','p_id' => 107,'paixu' => 2,'isupdate' => 0],
                ['id' => 10703,'title' => '教学副校长','p_id' => 107,'paixu' => 3,'isupdate' => 0],
                ['id' => 10704,'title' => '德育副校长','p_id' => 107,'paixu' => 4,'isupdate' => 0],
                ['id' => 10705,'title' => '教学主任','p_id' => 107,'paixu' => 5,'isupdate' => 0],
                ['id' => 10706,'title' => '德育主任','p_id' => 107,'paixu' => 6,'isupdate' => 0],
                ['id' => 107010,'title' => '其他','p_id' => 107,'paixu' => 10,'isupdate' => 0],

            ['id' => 108
                ,'title' => '学期'
                ,'p_id' => 0
                ,'paixu' => 8
                ,'isupdate' => 0
            ],
                //  学期
                ['id' => 10801,'title' => '第一学期','p_id' => 108,'paixu' => 1,'isupdate' => 0],
                ['id' => 10802,'title' => '第二学期','p_id' => 108,'paixu' => 2,'isupdate' => 0],
            ['id' => 109
                ,'title' => '考试'
                ,'p_id' => 0
                ,'paixu' => 9
                ,'isupdate' => 0
            ],
                //  考试分类
                ['id' => 10901,'title' => '期末考试','p_id' => 109,'paixu' => 1,'isupdate' => 0],
                ['id' => 10902,'title' => '期中考试','p_id' => 109,'paixu' => 2,'isupdate' => 0],
                ['id' => 10903,'title' => '单项测试','p_id' => 109,'paixu' => 3,'isupdate' => 0],
            ['id' => 110
                ,'title' => '学科'
                ,'p_id' => 0
                ,'paixu' => 10
                ,'isupdate' => 0
            ],
                //  大学科
                ['id' => 11001,'title' => '语文','p_id' => 110,'paixu' => 1,'isupdate' => 0],
                ['id' => 11002,'title' => '数学','p_id' => 110,'paixu' => 2,'isupdate' => 0],
                ['id' => 11003,'title' => '外语','p_id' => 110,'paixu' => 3,'isupdate' => 0],
                ['id' => 11004,'title' => '品德','p_id' => 110,'paixu' => 4,'isupdate' => 0],
                ['id' => 11005,'title' => '历史与社会','p_id' => 110,'paixu' => 5,'isupdate' => 0],
                ['id' => 11006,'title' => '科学','p_id' => 110,'paixu' => 6,'isupdate' => 0],
                ['id' => 11007,'title' => '体育与健康','p_id' => 110,'paixu' => 7,'isupdate' => 0],
                ['id' => 11008,'title' => '艺术','p_id' => 110,'paixu' => 8,'isupdate' => 0],
                ['id' => 11009,'title' => '综合实践活动','p_id' => 110,'paixu' => 9,'isupdate' => 0],
                ['id' => 11010,'title' => '地方/校本课程','p_id' => 110,'paixu' => 10,'isupdate' => 0],
                ['id' => 11011,'title' => '幼儿园全科','p_id' => 110,'paixu' => 11,'isupdate' => 0],
                ['id' => 11012,'title' => '其它','p_id' => 110,'paixu' => 12,'isupdate' => 0],
            ['id' => 111
                ,'title' => '文件'
                ,'p_id' => 0
                ,'paixu' => 11
                ,'isupdate' => 0
            ],
                //  文件
                ['id' => 11101,'title' => '教师名单','p_id' => 111,'paixu' => 1,'isupdate' => 0],
                ['id' => 11102,'title' => '学生名单','p_id' => 111,'paixu' => 2,'isupdate' => 0],
                ['id' => 11103,'title' => '考试成绩','p_id' => 111,'paixu' => 3,'isupdate' => 0],
            ['id' => 112
                ,'title' => '单位荣誉类型'
                ,'p_id' => 0
                ,'paixu' => 12
                ,'isupdate' => 0
            ],
                //  单位荣誉分类
                ['id' => 11201,'title' => '科研','p_id' => 112,'paixu' => 1,'isupdate' => 0],
                ['id' => 11202,'title' => '特色','p_id' => 112,'paixu' => 2,'isupdate' => 0],
                ['id' => 11203,'title' => '教研','p_id' => 112,'paixu' => 3,'isupdate' => 0],
            ['id' => 113
                ,'title' => '荣誉奖项'
                ,'p_id' => 0
                ,'paixu' => 13
                ,'isupdate' => 0
            ],
                //  单位荣誉奖项
                ['id' => 11301,'title' => '先进(个人/单位)','p_id' => 113,'paixu' => 1,'isupdate' => 0],
                ['id' => 11302,'title' => '一等奖','p_id' => 113,'paixu' => 2,'isupdate' => 0],
                ['id' => 11303,'title' => '二等奖','p_id' => 113,'paixu' => 3,'isupdate' => 0],
                ['id' => 11304,'title' => '三等奖','p_id' => 113,'paixu' => 4,'isupdate' => 0],
                ['id' => 11305,'title' => '优秀奖','p_id' => 113,'paixu' => 5,'isupdate' => 0],
                ['id' => 11306,'title' => '百十佳','p_id' => 113,'paixu' => 6,'isupdate' => 0],
                ['id' => 11307,'title' => '指导奖','p_id' => 113,'paixu' => 7,'isupdate' => 0],
                ['id' => 11308,'title' => '其他','p_id' => 113,'paixu' => 8,'isupdate' => 0],
            ['id' => 114
                ,'title' => '教师荣誉类型'
                ,'p_id' => 0
                ,'paixu' => 14
                ,'isupdate' => 0
            ],
                //  教师荣誉分类
                ['id' => 11401,'title' => '优质课','p_id' => 114,'paixu' => 1,'isupdate' => 0],
                ['id' => 11402,'title' => '技能大赛','p_id' => 114,'paixu' => 2,'isupdate' => 0],
                ['id' => 11403,'title' => '论文','p_id' => 114,'paixu' => 3,'isupdate' => 0],
                ['id' => 11404,'title' => '教科研','p_id' => 114,'paixu' => 4,'isupdate' => 0],
                ['id' => 11405,'title' => '荣誉称号','p_id' => 114,'paixu' => 5,'isupdate' => 0],
                ['id' => 11406,'title' => '培训','p_id' => 114,'paixu' => 6,'isupdate' => 0],
                ['id' => 11407,'title' => '展示课','p_id' => 114,'paixu' => 7,'isupdate' => 0],
            ['id' => 115
                ,'title' => '课题立项类型'
                ,'p_id' => 0
                ,'paixu' => 15
                ,'isupdate' => 0
            ],
                //  课题立项类型
                ['id' => 11501,'title' => '一般课题','p_id' => 115,'paixu' => 1,'isupdate' => 0],
                ['id' => 11502,'title' => '专项课题','p_id' => 115,'paixu' => 2,'isupdate' => 0],
                ['id' => 11503,'title' => '重大或重点课题','p_id' => 115,'paixu' => 3,'isupdate' => 0],
                ['id' => 11504,'title' => '子课题','p_id' => 115,'paixu' => 4,'isupdate' => 0],
            ['id' => 116
                ,'title' => '课题研究所属学科分类'
                ,'p_id' => 0
                ,'paixu' => 16
                ,'isupdate' => 0
            ],
                //  课题研究所属学科分类
                ['id' => 11601,'title' => 'A. 教育政策研究(含教育发展战略)','p_id' => 116,'paixu' => 1,'isupdate' => 0],
                ['id' => 11602,'title' => 'B. 基础教育','p_id' => 116,'paixu' => 2,'isupdate' => 0],
                ['id' => 11603,'title' => 'C. 职业教育与成人教育(含终身教育、社会教育)','p_id' => 116,'paixu' => 3,'isupdate' => 0],
                ['id' => 11604,'title' => 'D.其他','p_id' => 116,'paixu' => 3,'isupdate' => 0],
            ['id' => 117
                ,'title' => '研究类型'
                ,'p_id' => 0
                ,'paixu' => 17
                ,'isupdate' => 0
            ],
                //  研究类型
                ['id' => 11701,'title' => 'A.基础研究','p_id' => 117,'paixu' => 1,'isupdate' => 0],
                ['id' => 11702,'title' => 'B.应用研究','p_id' => 117,'paixu' => 2,'isupdate' => 0],
                ['id' => 11703,'title' => 'C.综合研究','p_id' => 117,'paixu' => 3,'isupdate' => 0],
            ['id' => 118
                ,'title' => '课题状态'
                ,'p_id' => 0
                ,'paixu' => 18
                ,'isupdate' => 0
            ],
                //  结题等级类型
                ['id' => 11801,'title' => '研究中','p_id' => 118,'paixu' => 1,'isupdate' => 0],
                ['id' => 11802,'title' => '合格','p_id' => 118,'paixu' => 2,'isupdate' => 0],
                ['id' => 11803,'title' => '优秀','p_id' => 118,'paixu' => 3,'isupdate' => 0],
                ['id' => 11804,'title' => '流失','p_id' => 118,'paixu' => 3,'isupdate' => 0],
            ['id' => 119
                ,'title' => '课题或荣誉角色'
                ,'p_id' => 0
                ,'paixu' => 19
                ,'isupdate' => 0
            ],
                //  课题或荣誉角色
                ['id' => 11901,'title' => '主持人','p_id' => 119,'paixu' => 1,'isupdate' => 0],
                ['id' => 11902,'title' => '参与人','p_id' => 119,'paixu' => 2,'isupdate' => 0],
            ['id' => 120
                ,'title' => '成绩统计分类'
                ,'p_id' => 0
                ,'paixu' => 20
                ,'isupdate' => 0
            ],
                //  课题或荣誉角色
                ['id' => 12001,'title' => '班级统计','p_id' => 120,'paixu' => 1,'isupdate' => 0],
                ['id' => 12002,'title' => '班级排序','p_id' => 120,'paixu' => 2,'isupdate' => 0],
                ['id' => 12003,'title' => '学校统计','p_id' => 120,'paixu' => 3,'isupdate' => 0],
                ['id' => 12004,'title' => '学校排序','p_id' => 120,'paixu' => 4,'isupdate' => 0],
                ['id' => 12005,'title' => '区统计','p_id' => 120,'paixu' => 5,'isupdate' => 0],
                ['id' => 12006,'title' => '区排序','p_id' => 120,'paixu' => 6,'isupdate' => 0],
            ['id' => 121
                ,'title' => '成绩录入用户类型'
                ,'p_id' => 0
                ,'paixu' => 21
                ,'isupdate' => 0
            ],
                //  课题或荣誉角色
                ['id' => 12101,'title' => '班级统计','p_id' => 121,'paixu' => 1,'isupdate' => 0],
                ['id' => 12102,'title' => '班级排序','p_id' => 121,'paixu' => 2,'isupdate' => 0],
            // 统计项目
            ['id' => 122
                ,'title' => '统计项目'
                ,'p_id' => 0
                ,'paixu' => 22
                ,'isupdate' => 0
            ],
                //  统计项目位置
                ['id' => 12201,'title' => '教师-各班级-网页','p_id' => 122,'paixu' => 1,'isupdate' => 0],
                ['id' => 12202,'title' => '教师-各班级-下载','p_id' => 122,'paixu' => 2,'isupdate' => 0],
                ['id' => 12203,'title' => '教师-各学校-网页','p_id' => 122,'paixu' => 3,'isupdate' => 0],
                ['id' => 12204,'title' => '教师-各学校-下载','p_id' => 122,'paixu' => 4,'isupdate' => 0],
                ['id' => 12205,'title' => '教师-学生成绩列表-网页','p_id' => 122,'paixu' => 5,'isupdate' => 0],
                ['id' => 12206,'title' => '教师-学生成绩列表-下载右','p_id' => 122,'paixu' => 6,'isupdate' => 0],
                ['id' => 12207,'title' => '教师-各学科-网页','p_id' => 122,'paixu' => 7,'isupdate' => 0],
                ['id' => 12208,'title' => '教师-各学生-网页','p_id' => 122,'paixu' => 8,'isupdate' => 0],
                ['id' => 12209,'title' => '学生-各考试-网页','p_id' => 122,'paixu' => 9,'isupdate' => 0,'status'=>0],
                ['id' => 12210,'title' => '教师-学生成绩列表-下载左','p_id' => 122,'paixu' => 10,'isupdate' => 0],
                ['id' => 12211,'title' => '教师-学生成绩列表-成绩条(学生)','p_id' => 122,'paixu' => 10,'isupdate' => 0],
                ['id' => 12212,'title' => '教师-学生成绩列表-成绩条(班级)','p_id' => 122,'paixu' => 10,'isupdate' => 0],
            // 教师评语
            ['id' => 123
                ,'title' => '教师评语'
                ,'p_id' => 0
                ,'paixu' => 23
                ,'isupdate' => 0
            ],
                //  教师评语
                ['id' => 12301,'title' => '学年评语','p_id' => 123,'paixu' => 1,'isupdate' => 0],
                ['id' => 12302,'title' => '学期评语','p_id' => 123,'paixu' => 2,'isupdate' => 0],
                ['id' => 12303,'title' => '月评语','p_id' => 123,'paixu' => 3,'isupdate' => 0],
                ['id' => 12304,'title' => '周评语','p_id' => 123,'paixu' => 4,'isupdate' => 0],
                ['id' => 12305,'title' => '日常评语','p_id' => 123,'paixu' => 5,'isupdate' => 0],
            // 考试查看范围
            ['id' => 124
                ,'title' => '查看考试范围'
                ,'p_id' => 0
                ,'paixu' => 24
                ,'isupdate' => 0
            ],
                //  考试查询权限
                ['id' => 12401,'title' => '全区','p_id' => 124,'paixu' => 1,'isupdate' => 0],
                ['id' => 12402,'title' => '全校','p_id' => 124,'paixu' => 2,'isupdate' => 0],
                ['id' => 12403,'title' => '年级全学科','p_id' => 124,'paixu' => 3,'isupdate' => 0],
                ['id' => 12404,'title' => '年级学科','p_id' => 124,'paixu' => 4,'isupdate' => 0],
                ['id' => 12405,'title' => '班级全学科','p_id' => 124,'paixu' => 5,'isupdate' => 0],
                ['id' => 12406,'title' => '班级学科','p_id' => 124,'paixu' => 6,'isupdate' => 0],
            // 考试查看范围
            ['id' => 125
                ,'title' => '教研组类别'
                ,'p_id' => 0
                ,'paixu' => 25
                ,'isupdate' => 0
            ],
                //  考试查询权限
                ['id' => 12501,'title' => '年级组','p_id' => 125,'paixu' => 1,'isupdate' => 0],
                ['id' => 12502,'title' => '学科组','p_id' => 125,'paixu' => 2,'isupdate' => 0],
            ['id' => 126
                ,'title' => '房间功能'
                ,'p_id' => 0
                ,'paixu' => 26
                ,'isupdate' => 0
            ],
                //  考试查询权限
                ['id' => 12601,'title' => '办公室','p_id' => 126,'paixu' => 1,'isupdate' => 0],
                ['id' => 12602,'title' => '学生教室','p_id' => 126,'paixu' => 2,'isupdate' => 0],
                ['id' => 12603,'title' => '专用教室','p_id' => 126,'paixu' => 3,'isupdate' => 0],
        ];

        $serRows = $this->fetchAll('select * from cj_category');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = array();
            return true;
        }

        // 保存数据
        $this->table('category')->insert($rows)->save();
    }
}
