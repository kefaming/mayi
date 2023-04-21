<?php

use think\migration\Seeder;

class Rule extends Seeder
{
    /**
     * run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        // 01010101 1-2一级、3-4二级、5-6三级、7-8四级
        // 初始化超级教师
        $rows = [
            /*======================================================================
             * 成绩采集
             */
            ['id' => 1 #01
                ,'title'  => '成绩采集'
                ,'name' => 'chengji'
                ,'paixu'  => 1
                ,'ismenu'  => 1
                ,'font' => '&#xe6c9;'
            ],
            ['id' => 101
                ,'title' => '扫码录入'
                ,'name' => 'luru/index/malu'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 1
                ,'url' => '/luru/index/malu'
            ],
                ['id' => 10101
                    ,'title' => '扫码查询'
                    ,'name' => 'luru/index/read'
                    ,'paixu'  => 1
                    ,'pid' => 101
                ]
                ,['id' => 10102
                    ,'title' => '扫码保存'
                    ,'name' => 'luru/index/malusave'
                    ,'paixu'  => 2
                    ,'pid' => 101
                ],
            ['id' => 102
                ,'title' => '表格录入'
                ,'name' => 'luru/index/biaolu'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 1
                ,'url' => '/luru/index/biaolu'
            ],
                ['id' => 10201
                    ,'title' => '表格保存'
                    ,'name' => 'luru/index/saveall'
                    ,'paixu'  => 1
                    ,'pid' => 102
                ],
                ['id' => 10202
                    ,'title' => '表格上传'
                    ,'name' => 'luru/index/upload'
                    ,'paixu'  => 2
                    ,'pid' => 102
                ],
                ['id' => 10203
                    ,'title' => '下载表格'
                    ,'name' => 'luru/index/caiji'
                    ,'paixu' => 3
                    ,'pid' => 102
                ],
            ['id' => 103
                ,'title' => '已录列表'
                ,'name' => 'luru/index/index'
                ,'paixu' => 4
                ,'ismenu'  => 1
                ,'pid'  => 1
                ,'url' => '/luru/index/index'
            ],
                ['id' => 10301
                    ,'title' => '成绩更新'
                    ,'name' => 'luru/index/update'
                    ,'paixu'  => 1
                    ,'pid' => 103
                ],
                // 成绩状态
                ['id' => 10302
                    ,'title' => '成绩状态'
                    ,'name' => 'chengji/index/setstatus'
                    ,'paixu' => 2
                    ,'pid' => 103
                ],
            ['id' => 105
                ,'title' => '已录统计'
                ,'name' => 'luru/index/yilucnt'
                ,'paixu' => 5
                ,'ismenu'  => 1
                ,'pid' => 1
                ,'url' => '/luru/index/yilucnt'
            ],
                // 占位
                ['id' => 10501
                    ,'title' => '已录统计列表'
                    ,'name' => 'luru/index/yilucnttable'
                    ,'paixu' => 1
                    ,'pid' => 105
                ],
            ['id' => 106
                ,'title' => '下载标签'
                ,'name' => 'luru/index/biaoqian'
                ,'paixu' => 6
                ,'ismenu'  => 1
                ,'pid'  => 1
                ,'url' => '/luru/index/biaoqian'
            ],
                // 占位
                ['id' => 10601
                    ,'title' => '获取标签数据'
                    ,'name' => 'luru/index/biaoqianxls'
                    ,'paixu' => 1
                    ,'pid' => 106
                ],
            ['id' => 107
                ,'title' => '在线录入'
                ,'name' => 'luru/index/online'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 1
                ,'url' => '/luru/index/online'
            ],
                // 占位
                ['id' => 10701
                    ,'title' => '编辑'
                    ,'name' => 'luru/index/onlineedit'
                    ,'paixu' => 1
                    ,'pid' => 107
                ],

            /*======================================================================
             * 考试管理
             */
            ['id' => 3
                ,'title'  => '考试管理'
                ,'name' => 'kaoshi'
                ,'paixu'  => 2
                ,'ismenu'  => 1
                ,'font' => '&#xe6ee;'
            ],
            ['id' => 301
                ,'title' => '考试列表'
                ,'name' => 'kaoshi/index/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 3
                ,'url' => '/kaoshi/index'
            ],
                ['id' => 30101
                    ,'title' => '添加'
                    ,'name' => 'kaoshi/index/create'
                    ,'paixu'  => 1
                    ,'pid' => 301
                ],
                ['id' => 30102
                    ,'title' => '删除'
                    ,'name' => 'kaoshi/index/delete'
                    ,'paixu' => 2
                    ,'pid' => 301
                ],
                ['id' => 30103
                    ,'title' => '编辑'
                    ,'name' => 'kaoshi/index/edit'
                    ,'paixu' => 3
                    ,'pid' => 301
                ],
                ['id' => 30104
                    ,'title' => '更新'
                    ,'name' => 'kaoshi/index/update'
                    ,'paixu' => 4
                    ,'pid' => 301
                ],
                ['id' => 30105
                    ,'title' => '保存'
                    ,'name' => 'kaoshi/index/save'
                    ,'paixu' => 5
                    ,'pid' => 301
                ],
                ['id' => 30106
                    ,'title' => '状态'
                    ,'name' => 'kaoshi/index/setstatus'
                    ,'paixu' => 6
                    ,'pid' => 301
                ],
                ['id' => 30107
                    ,'title' => '成绩编辑'
                    ,'name' => 'kaoshi/index/luru'
                    ,'paixu' => 7
                    ,'pid' => 301
                ],
            ['id' => 302
                ,'title' => '考试操作'
                ,'name' => 'kaoshi/index/moreaction'
                ,'paixu' => 2
                ,'ismenu'  => 0
                ,'pid'  => 3
            ],
                ['id' => 30201
                    ,'title' => '前期操作'
                    ,'name' => 'yiqianqi'
                    ,'paixu' => 1
                    ,'pid' => 302
                ],
                    ['id' => 3020101
                        ,'title' => '考试设置'
                        ,'name' => 'kaoshi/kaoshiset/index'
                        ,'paixu' => 1
                        ,'pid' => 30201
                    ],
                        ['id' => 302010102
                            ,'title' => '新建'
                            ,'name' => 'kaoshi/kaoshiset/create'
                            ,'paixu' => 1
                            ,'pid' => 3020101
                        ],
                        ['id' => 302010103
                            ,'title' => '保存'
                            ,'name' => 'kaoshi/kaoshiset/save'
                            ,'paixu' => 2
                            ,'pid' => 3020101
                        ],
                        ['id' => 302010104
                            ,'title' => '删除'
                            ,'name' => 'kaoshi/kaoshiset/delete'
                            ,'paixu' => 3
                            ,'pid' => 3020101
                        ],
                        ['id' => 302010105
                            ,'title' => '编辑'
                            ,'name' => 'kaoshi/kaoshiset/edit'
                            ,'paixu' => 4
                            ,'pid' => 3020101
                        ],
                        ['id' => 302010106
                            ,'title' => '更新'
                            ,'name' => 'kaoshi/kaoshiset/update'
                            ,'paixu' => 5
                            ,'pid' => 3020101
                        ],
                        ['id' => 302010107
                            ,'title' => '状态'
                            ,'name' => 'kaoshi/kaoshiset/setstatus'
                            ,'paixu' => 6
                            ,'pid' => 3020101
                        ],
                        ['id' => 302010109
                            ,'title' => '更新得分率'
                            ,'name' => 'chengji/tongji/updatedefenLv'
                            ,'paixu' => 5
                            ,'pid' => 3020101
                        ],
                    ['id' => 3020102
                        ,'title' => '生成考号'
                        ,'name' => 'kaohao/index/createall'
                        ,'paixu' => 1
                        ,'pid' => 30201
                    ],
                        ['id' => 302010201
                            ,'title' => '生成考号备用'
                            ,'name' => 'zhanwei_302010201'
                            ,'paixu' => 2
                            ,'pid' => 3020102
                        ],
                    ['id' => 3020103
                        ,'title' => '任课教师'
                        ,'name' => 'chengji/bjtongji/renke'
                        ,'paixu' => 1
                        ,'pid' => 30201
                    ],
                        ['id' => 302010301
                            ,'title' => '编辑'
                            ,'name' => 'chengji/bjtongji/renkeedit'
                            ,'paixu' => 2
                            ,'pid' => 3020103
                        ],
                        ['id' => 302010302
                            ,'title' => '更新编辑'
                            ,'name' => 'chengji/bjtongji/renkeupdate'
                            ,'paixu' => 2
                            ,'pid' => 3020103
                        ],
                        ['id' => 302010303
                            ,'title' => '设置'
                            ,'name' => 'chengji/bjtongji/renkeeditteacher'
                            ,'paixu' => 2
                            ,'pid' => 3020103
                        ],
                        ['id' => 302010304
                            ,'title' => '更新设置'
                            ,'name' => 'chengji/bjtongji/renkeupdateteacher'
                            ,'paixu' => 2
                            ,'pid' => 3020103
                        ],
                    ['id' => 3020104
                        ,'title' => '创建考试向导'
                        ,'name' => 'kaoshi/index/xiangdao'
                        ,'paixu' => 4
                        ,'pid' => 30201
                    ],
                        ['id' => 302010401
                            ,'title' => '创建考试向导 Setp1'
                            ,'name' => 'kaoshi/index/createSetp1'
                            ,'paixu' => 1
                            ,'pid' => 3020104
                        ],
                        ['id' => 302010402
                            ,'title' => '创建考试向导 Setp1 修改'
                            ,'name' => 'kaoshi/index/editSetp1'
                            ,'paixu' => 1
                            ,'pid' => 3020104
                        ],
                        ['id' => 302010403
                            ,'title' => '创建考试向导 Setp2'
                            ,'name' => 'kaoshi/index/createSetp2'
                            ,'paixu' => 2
                            ,'pid' => 3020104
                        ],
                        ['id' => 302010404
                            ,'title' => '创建考试向导 Setp3'
                            ,'name' => 'kaoshi/index/createSetp3'
                            ,'paixu' => 3
                            ,'pid' => 3020104
                        ],
                        ['id' => 302010405
                            ,'title' => '创建考试向导 Setp4'
                            ,'name' => 'kaoshi/index/createSetp4'
                            ,'paixu' => 4
                            ,'pid' => 3020104
                        ],
                    ['id' => 3020105
                        ,'title' => '录入成绩分工列表'
                        ,'name' => 'kaoshi/lurufengong/index'
                        ,'paixu' => 5
                        ,'pid' => 30201
                    ],
                        ['id' => 302010501
                            ,'title' => '添加'
                            ,'name' => 'kaoshi/lurufengong/create'
                            ,'paixu' => 1
                            ,'pid' => 3020105
                        ],
                        ['id' => 302010502
                            ,'title' => '保存'
                            ,'name' => 'kaoshi/lurufengong/save'
                            ,'paixu' => 2
                            ,'pid' => 3020105
                        ],
                        ['id' => 302010503
                            ,'title' => '删除'
                            ,'name' => 'kaoshi/lurufengong/delete'
                            ,'paixu' => 3
                            ,'pid' => 3020105
                        ],

                ['id' => 30203
                    ,'title' => '成绩统计'
                    ,'name' => 'santongji'
                    ,'paixu' => 4
                    ,'pid' => 302
                ],
                    ['id' => 3020301
                        ,'title' => '以班级为单位统计成绩'
                        ,'name' => 'chengji/bjtongji/tongji'
                        ,'paixu' => 1
                        ,'pid' => 30203
                    ],
                    ['id' => 3020302
                        ,'title' => '以学校为单位统计成绩'
                        ,'name' => 'chengji/njtongji/tongji'
                        ,'paixu' => 2
                        ,'pid' => 30203
                    ],
                    ['id' => 3020303
                        ,'title' => '以全部成绩为单位统计成绩'
                        ,'name' => 'chengji/schtongji/tongji'
                        ,'paixu' => 3
                        ,'pid' => 30203
                    ],
                    ['id' => 3020304
                        ,'title' => '统计学生成绩在班级位置'
                        ,'name' => 'chengji/bjtongji/bjorder'
                        ,'paixu' => 4
                        ,'pid' => 30203
                    ],
                    ['id' => 3020305
                        ,'title' => '统计学生成绩在学校位置'
                        ,'name' => 'chengji/njtongji/njorder'
                        ,'paixu' => 5
                        ,'pid' => 30203
                    ],
                    ['id' => 3020306
                        ,'title' => '统计学生成绩在区位置'
                        ,'name' => 'chengji/schtongji/schorder'
                        ,'paixu' => 6
                        ,'pid' => 30203
                    ],
                    ['id' => 3020307
                        ,'title' => '检测统计结果'
                        ,'name' => 'kaoshi/tjlog/index'
                        ,'paixu' => 7
                        ,'pid' => 30203
                    ],
                    ['id' => 3020308
                        ,'title' => '一键统计'
                        ,'name' => 'kaoshi/index/tongji'
                        ,'paixu' => 8
                        ,'pid' => 30203
                    ],
                ['id' => 30204
                    ,'title' => '查看成绩统计结果'
                    ,'name' => 'kaoshi/index/chengji'
                    ,'paixu' => 4
                    ,'pid' => 302
                ],
                    ['id' => 3020401
                        ,'title' => '学生成绩'
                        ,'name' => 'chengji/index/index'
                        ,'paixu' => 1
                        ,'pid' => 30204
                    ],
                        ['id' => 302040101
                            ,'title' => '录入人信息'
                            ,'name' => 'chengji/index/readadd'
                            ,'paixu' => 1
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040102
                            ,'title' => '考号删除'
                            ,'name' => 'kaohao/index/delete'
                            ,'paixu' => 2
                            ,'pid' => 3020401
                        ],
                            ['id' => 30204011
                                ,'title' => '删除单条成绩'
                                ,'name' => 'chengji/index/delete'
                                ,'paixu' => 3
                                ,'pid' => 3020401
                            ],
                        ['id' => 302040103
                            ,'title' => '批量删除界面'
                            ,'name' => 'chengji/index/deletecjs'
                            ,'paixu' => 4
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040104
                            ,'title' => '批量删除'
                            ,'name' => 'chengji/index/deletecjmore'
                            ,'paixu' => 5
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040105
                            ,'title' => '下载学生成绩条'
                            ,'name' => 'chengji/index/dwchengjitiao'
                            ,'paixu' => 6
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040106
                            ,'title' => '添加单个考号'
                            ,'name' => 'kaohao/index/create'
                            ,'paixu' => 7
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040107
                            ,'title' => '保存单个考号'
                            ,'name' => 'kaohao/index/save'
                            ,'paixu' => 8
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040108
                            ,'title' => '学生成绩图表'
                            ,'name' => 'kaohao/index/read'
                            ,'paixu' => 9
                            ,'pid' => 3020401
                        ],
                        ['id' => 302040109
                            ,'title' => '下载学生成绩'
                            ,'name' => 'chengji/index/dwchengji'
                            ,'paixu' => 10
                            ,'pid' => 3020401
                        ],
                    // 班级成绩统计表
                    ['id' => 3020402
                        ,'title' => '班级成绩'
                        ,'name' => 'banjichengji'
                        ,'paixu' => 2
                        ,'pid' => 30204
                    ],
                        ['id' => 302040201
                            ,'title' => '班级成绩统计'
                            ,'name' => 'chengji/bjtongji/biaoge'
                            ,'paixu' => 1
                            ,'pid' => 3020402
                        ],
                        ['id' => 302040202
                            ,'title' => '下载班级成绩统计表'
                            ,'name' => 'chengji/bjtongji/dwBiaoge'
                            ,'paixu' => 2
                            ,'pid' => 3020402
                        ],
                        ['id' => 302040203
                            ,'title' => '条形统计图'
                            ,'name' => 'chengji/bjtongji/myavg'
                            ,'paixu' => 3
                            ,'pid' => 3020402
                        ],
                        ['id' => 302040204
                            ,'title' => '箱体图'
                            ,'name' => 'chengji/bjtongji/myxiangti'
                            ,'paixu' => 4
                            ,'pid' => 3020402
                        ],
                    // 年级成绩统计表
                    ['id' => 3020403
                        ,'title' => '年级成绩'
                        ,'name' => 'nianjichengji'
                        ,'paixu' => 3
                        ,'pid' => 30204
                    ],
                        ['id' => 302040301
                            ,'title' => '年级成绩统计'
                            ,'name' => 'chengji/njtongji/biaoge'
                            ,'paixu' => 1
                            ,'pid' => 3020403
                        ],
                        ['id' => 302040302
                            ,'title' => '下载年级成绩统计表'
                            ,'name' => 'chengji/njtongji/dwBiaoge'
                            ,'paixu' => 2
                            ,'pid' => 3020403
                        ],
                        ['id' => 302040303
                            ,'title' => '条形统计图'
                            ,'name' => 'chengji/njtongji/myavg'
                            ,'paixu' => 3
                            ,'pid' => 3020403
                        ],
                        ['id' => 302040304
                            ,'title' => '箱体图'
                            ,'name' => 'chengji/njtongji/myxiangti'
                            ,'paixu' => 4
                            ,'pid' => 3020403
                        ],
                    // 统计记录
                    ['id' => 3020404
                        ,'title' => '统计记录'
                        ,'name' => 'kaoshi/tongjiLog/index'
                        ,'paixu' => 4
                        ,'pid' => 30204
                    ],
                        ['id' => 302040401
                            ,'title' => '占位'
                            ,'name' => 'tongjiLog1'
                            ,'paixu' => 1
                            ,'pid' => 3020404
                        ],
                    // 班级成绩统计表
                    ['id' => 3020405
                        ,'title' => '各分数段统计'
                        ,'name' => 'chengji/bjtongji/fenshuduan'
                        ,'paixu' => 5
                        ,'pid' => 30204
                    ],
                        ['id' => 302040501
                            ,'title' => '点位'
                            ,'name' => 'fenshudua'
                            ,'paixu' => 1
                            ,'pid' => 3020405
                        ],

            /*======================================================================
             * 学生管理
             */
            ['id' => 4
                ,'title'  => '学生管理'
                ,'name' => 'student'
                ,'paixu'  => 3
                ,'ismenu'  => 1
                ,'font' => '&#xe753;'
            ],
            ['id' => 401
                ,'title' => '学生列表'
                ,'name' => 'student/index/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 4
                ,'url' => '/student/index/index'
            ],
                // 学生管理权限
                ['id' => 40101
                    ,'title' => '添加'
                    ,'name' => 'student/index/create'
                    ,'paixu'  => 1
                    ,'pid' => 401
                ],
                ['id' => 40102
                    ,'title' => '保存'
                    ,'name' => 'student/index/save'
                    ,'paixu' => 2
                    ,'pid' => 401
                ],
                ['id' => 40103
                    ,'title' => '删除'
                    ,'name' => 'student/index/delete'
                    ,'paixu' => 3
                    ,'pid' => 401
                ],
                ['id' => 40104
                    ,'title' => '编辑'
                    ,'name' => 'student/index/edit'
                    ,'paixu' => 4
                    ,'pid' => 401
                ],
                ['id' => 40105
                    ,'title' => '更新'
                    ,'name' => 'student/index/update'
                    ,'paixu' => 5
                    ,'pid' => 401
                ],
                ['id' => 40106
                    ,'title' => '查看信息'
                    ,'name' => 'student/index/read'
                    ,'paixu' => 6
                    ,'pid' => 401
                ]
                ,['id' => 40107
                   ,'title' => '状态'
                    ,'name' => 'student/index/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 401
                ],
                ['id' => 40108
                    ,'title' => '是否参加考试'
                    ,'name' => 'student/index/setkaoshi'
                    ,'paixu' => 8
                    ,'pid' => 401
                ],
                ['id' => 40109
                    ,'title' => '下载模板'
                    ,'name' => 'student/index/download'
                    ,'paixu' => 9
                    ,'pid' => 401
                ],
                ['id' => 40110
                    ,'title' => '校对导入'
                    ,'name' => 'student/index/createall'
                    ,'paixu' => 10
                    ,'pid' => 401
                ],
                ['id' => 4011
                    ,'title' => '批量保存'
                    ,'name' => 'student/index/saveall'
                    ,'paixu' => 11
                    ,'pid' => 401
                ],
                ['id' => 40112
                    ,'title' => '表格删除页面'
                    ,'name' => 'student/index/deletes'
                    ,'paixu' => 12
                    ,'pid' => 401
                ],
                ['id' => 40113
                    ,'title' => '表格删除数据'
                    ,'name' => 'student/index/deletexlsx'
                    ,'paixu' => 13
                    ,'pid' => 401
                ],
                ['id' => 40114
                    ,'title' => '查看成绩'
                    ,'name' => 'student/chengji/index'
                    ,'paixu' => 14
                    ,'pid' => 401
                ],
                ['id' => 40115
                    ,'title' => '重置密码'
                    ,'name' => 'student/index/resetpassword'
                    ,'paixu' => 15
                    ,'pid' => 401
                ],
                ['id' => 40116
                    ,'title' => '修改学生考号'
                    ,'name' => 'student/index/updateonekaohao'
                    ,'paixu' => 16
                    ,'pid' => 401
                ],
            // 毕业名单
            ['id' => 402
                ,'title' => '毕业学生'
                ,'name' => 'student/index/bylist'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 4
                ,'url' => '/student/index/bylist'
            ],
                // 毕业学生
                ['id' => 40201
                    ,'title' => '格式占位'
                    ,'name' => 'zhanwei_40201'
                    ,'paixu'  => 1
                    ,'pid' => 402
                ],
            // 删除名单
            ['id' => 403
                ,'title' => '删除学生'
                ,'name' => 'student/index/dellist'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 4
                ,'url' => '/student/index/dellist'
            ],
                // 删除学生
                ['id' => 40301
                    ,'title' => '恢复删除'
                    ,'name' => 'student/index/redel'
                    ,'paixu' => 1
                    ,'pid' => 403
                ],



            /*======================================================================
             * 考试管理
             */
            ['id' => 5
                ,'title'  => '教务管理'
                ,'name' => 'teach'
                ,'paixu'  => 7
                ,'ismenu'  => 1
                ,'font' => '&#xe6da;'
            ],
            ['id' => 501
                ,'title' => '学期列表'
                ,'name' => 'teach/xueqi/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 5
                ,'url' => '/teach/xueqi'
            ],
                ['id' => 50101
                    ,'title' => '添加'
                    ,'name' => 'teach/xueqi/create'
                    ,'paixu'  => 1
                    ,'pid' => 501
                ],
                ['id' => 50102
                    ,'title' => '保存'
                    ,'name' => 'teach/xueqi/save'
                    ,'paixu'  => 2
                    ,'pid' => 501
                ],
                ['id' => 50103
                    ,'title' => '删除'
                    ,'name' => 'teach/xueqi/delete'
                    ,'paixu' => 3
                    ,'pid' => 501
                ],
                ['id' => 50104
                    ,'title' => '编辑'
                    ,'name' => 'teach/xueqi/edit'
                    ,'paixu' => 4
                    ,'pid' => 501
                ],
                ['id' => 50105
                    ,'title' => '更新'
                    ,'name' => 'teach/xueqi/update'
                    ,'paixu'  => 5
                    ,'pid' => 501
                ],
                ['id' => 50106
                    ,'title' => '查看'
                    ,'name' => 'teach/xueqi/read'
                    ,'paixu' => 6
                    ,'pid' => 501
                ],
                ['id' => 50107
                    ,'title' => '状态'
                    ,'name' => 'teach/xueqi/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 501
                ],
            ['id' => 502
                ,'title' => '班级列表'
                ,'name' => 'teach/banji/index'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 5
                ,'url' => '/teach/banji'
            ],
                ['id' => 50201
                    ,'title' => '添加'
                    ,'name' => 'teach/banji/create'
                    ,'paixu'  => 1
                    ,'pid' => 502
                ],
                ['id' => 50202
                    ,'title' => '保存'
                    ,'name' => 'teach/banji/save'
                    ,'paixu'  => 2
                    ,'pid' => 502
                ],
                ['id' => 50203
                    ,'title' => '移动'
                    ,'name' => 'teach/banji/yidong'
                    ,'paixu' => 3
                    ,'pid' => 502
                ],
                ['id' => 50204
                    ,'title' => '删除'
                    ,'name' => 'teach/banji/delete'
                    ,'paixu' => 4
                    ,'pid' => 502
                ],
                ['id' => 50205
                    ,'title' => '状态'
                    ,'name' => 'teach/banji/setstatus'
                    ,'paixu' => 5
                    ,'pid' => 502
                ],
                ['id' => 50206
                    ,'title' => '成绩查看'
                    ,'name' => 'teach/banjichengji/index'
                    ,'paixu' => 6
                    ,'pid' => 502
                ],
                ['id' => 50207
                    ,'title' => '设置别名'
                    ,'name' => 'teach/banji/setalias'
                    ,'paixu' => 7
                    ,'pid' => 502
                ],
                    ['id' => 5020701
                        ,'title' => '占位'
                        ,'name' => 'teach/banji/zhanwei1'
                        ,'paixu' => 1
                        ,'pid' => 50207
                    ],
                ['id' => 50208
                    ,'title' => '班主任管理'
                    ,'name' => 'teach/banzhuren/index'
                    ,'paixu' => 8
                    ,'pid' => 502
                ],
                    ['id' => 5020801
                        ,'title' => '添加'
                        ,'name' => 'teach/banzhuren/create'
                        ,'paixu' => 1
                        ,'pid' => 50208
                    ],
                    ['id' => 5020802
                        ,'title' => '保存'
                        ,'name' => 'teach/banzhuren/save'
                        ,'paixu' => 2
                        ,'pid' => 50208
                    ],
                    ['id' => 5020803
                        ,'title' => '编辑'
                        ,'name' => 'teach/banzhuren/edit'
                        ,'paixu' => 3
                        ,'pid' => 50208
                    ],
                    ['id' => 5020804
                        ,'title' => '更新'
                        ,'name' => 'teach/banzhuren/update'
                        ,'paixu' => 4
                        ,'pid' => 50208
                    ],
                    ['id' => 5020805
                        ,'title' => '删除'
                        ,'name' => 'teach/banzhuren/delete'
                        ,'paixu' => 5
                        ,'pid' => 50208
                    ],
                    ['id' => 5020806
                        ,'title' => '状态'
                        ,'name' => 'teach/banzhuren/setstatus'
                        ,'paixu' => 6
                        ,'pid' => 50208
                    ],
                ['id' => 50209
                        ,'title' => '学生列表'
                        ,'name' => 'teach/banji/student'
                        ,'paixu' => 9
                        ,'pid' => 502
                    ],
            ['id' => 503
                ,'title' => '学科列表'
                ,'name' => 'teach/subject/index'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 5
                ,'url' => '/teach/subject'
            ],
                // 学科列表权限
                ['id' => 50301
                    ,'title' => '添加'
                    ,'name' => 'teach/subject/create'
                    ,'paixu'  => 1
                    ,'pid' => 503
                ],
                ['id' => 50302
                    ,'title' => '保存'
                    ,'name' => 'teach/subject/save'
                    ,'paixu'  => 2
                    ,'pid' => 503
                ],
                ['id' => 50303
                    ,'title' => '删除'
                    ,'name' => 'teach/subject/delete'
                    ,'paixu' => 3
                    ,'pid' => 503
                ],
                ['id' => 50304
                    ,'title' => '恢复删除'
                    ,'name' => 'teach/subject/restoredel'
                    ,'paixu' => 4
                    ,'pid' => 503
                ],
                ['id' => 50305
                    ,'title' => '编辑'
                    ,'name' => 'teach/subject/edit'
                    ,'paixu' => 5
                    ,'pid' => 503
                ],
                ['id' => 50306
                    ,'title' => '更新'
                    ,'name' => 'teach/subject/update'
                    ,'paixu'  => 6
                    ,'pid' => 503
                ],
                ['id' => 50307
                    ,'title' => '查看'
                    ,'name' => 'teach/subject/read'
                    ,'paixu' => 7
                    ,'pid' => 503
                ],
                ['id' => 50308
                    ,'title' => '状态'
                    ,'name' => 'teach/subject/setstatus'
                    ,'paixu' => 8
                    ,'pid' => 503
                ],
                ['id' => 50309
                    ,'title' => '参加考试'
                    ,'name' => 'teach/subject/kaoshi'
                    ,'paixu' => 9
                    ,'pid' => 503
                ],
            ['id' => 504
                ,'title' => '教研组'
                ,'name' => 'teach/jiaoyanzu/index'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 5
                ,'url' => '/teach/jiaoyanzu'
            ],
                // 学科列表权限
                ['id' => 50401
                    ,'title' => '添加'
                    ,'name' => 'teach/jiaoyanzu/create'
                    ,'paixu'  => 1
                    ,'pid' => 504
                ],
                ['id' => 50402
                    ,'title' => '保存'
                    ,'name' => 'teach/jiaoyanzu/save'
                    ,'paixu'  => 2
                    ,'pid' => 504
                ],
                ['id' => 50403
                    ,'title' => '删除'
                    ,'name' => 'teach/jiaoyanzu/delete'
                    ,'paixu' => 3
                    ,'pid' => 504
                ],
                ['id' => 50404
                    ,'title' => '编辑'
                    ,'name' => 'teach/jiaoyanzu/edit'
                    ,'paixu' => 4
                    ,'pid' => 504
                ],
                ['id' => 50405
                    ,'title' => '更新'
                    ,'name' => 'teach/jiaoyanzu/update'
                    ,'paixu'  => 5
                    ,'pid' => 504
                ],
                ['id' => 50406
                    ,'title' => '查看'
                    ,'name' => 'teach/jiaoyanzu/read'
                    ,'paixu' => 6
                    ,'pid' => 504
                ],
                ['id' => 50407
                    ,'title' => '状态'
                    ,'name' => 'teach/jiaoyanzu/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 504
                ],
            ['id' => 505
                ,'title' => '教研组长'
                ,'name' => 'teach/jiaoyanzuzhang/index'
                ,'paixu' => 3
                ,'ismenu'  => 0
                ,'pid'  => 5
                ,'url' => '/teach/jiaoyanzuzhang'
            ],
                // 学科列表权限
                ['id' => 50501
                    ,'title' => '添加'
                    ,'name' => 'teach/jiaoyanzuzhang/create'
                    ,'paixu'  => 1
                    ,'pid' => 505
                ],
                ['id' => 50502
                    ,'title' => '保存'
                    ,'name' => 'teach/jiaoyanzuzhang/save'
                    ,'paixu'  => 2
                    ,'pid' => 505
                ],
                ['id' => 50503
                    ,'title' => '删除'
                    ,'name' => 'teach/jiaoyanzuzhang/delete'
                    ,'paixu' => 3
                    ,'pid' => 505
                ],
                ['id' => 50504
                    ,'title' => '编辑'
                    ,'name' => 'teach/jiaoyanzuzhang/edit'
                    ,'paixu' => 4
                    ,'pid' => 505
                ],
                ['id' => 50505
                    ,'title' => '更新'
                    ,'name' => 'teach/jiaoyanzuzhang/update'
                    ,'paixu'  => 5
                    ,'pid' => 505
                ],
                ['id' => 50506
                    ,'title' => '查看'
                    ,'name' => 'teach/jiaoyanzuzhang/read'
                    ,'paixu' => 6
                    ,'pid' => 505
                ],
                ['id' => 50507
                    ,'title' => '状态'
                    ,'name' => 'teach/jiaoyanzuzhang/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 505
                ],
            ['id' => 506
                ,'title' => '任务分工'
                ,'name' => 'teach/fengong/index'
                ,'paixu' => 6
                ,'ismenu'  => 1
                ,'pid'  => 6
                ,'url' => '/teach/fengong'
            ],
                // 学科列表权限
                ['id' => 50601
                    ,'title' => '添加'
                    ,'name' => 'teach/fengong/create'
                    ,'paixu'  => 1
                    ,'pid' => 506
                ],
                ['id' => 50602
                    ,'title' => '保存'
                    ,'name' => 'teach/fengong/save'
                    ,'paixu'  => 2
                    ,'pid' => 506
                ],
                ['id' => 50603
                    ,'title' => '删除'
                    ,'name' => 'teach/fengong/delete'
                    ,'paixu' => 3
                    ,'pid' => 506
                ],
                ['id' => 50604
                    ,'title' => '编辑'
                    ,'name' => 'teach/fengong/edit'
                    ,'paixu' => 4
                    ,'pid' => 506
                ],
                ['id' => 50605
                    ,'title' => '更新'
                    ,'name' => 'teach/fengong/update'
                    ,'paixu'  => 5
                    ,'pid' => 506
                ],
                ['id' => 50606
                    ,'title' => '查看'
                    ,'name' => 'teach/fengong/read'
                    ,'paixu' => 6
                    ,'pid' => 506
                ],
                ['id' => 50607
                    ,'title' => '状态'
                    ,'name' => 'teach/fengong/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 506
                ],

            /*======================================================================
             * 考试管理
             */
            ['id' => 6
                ,'title'  => '教师管理'
                ,'name' => 'admin'
                ,'paixu'  => 8
                ,'ismenu'  => 1
                ,'font' => '&#xe6b8;'
            ],
            /*====================================*/
            ['id' => 601
                ,'title' => '教师列表'
                ,'name' => 'admin/index/index'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 6
                ,'url' => '/admin/index'
            ],
                // 教师列表权限
                ['id' => 60101
                    ,'title' => '添加'
                    ,'name' => 'admin/index/create'
                    ,'paixu'  => 1
                    ,'pid' => 601
                ],
                ['id' => 60102
                    ,'title' => '保存'
                    ,'name' => 'admin/index/save'
                    ,'paixu'  => 2
                    ,'pid' => 601
                ],
                ['id' => 60103
                    ,'title' => '删除'
                    ,'name' => 'admin/index/delete'
                    ,'paixu' => 3
                    ,'pid' => 601
                ],
                ['id' => 60104
                    ,'title' => '编辑'
                    ,'name' => 'admin/index/edit'
                    ,'paixu' => 4
                    ,'pid' => 601
                ],
                ['id' => 60105
                    ,'title' => '更新'
                    ,'name' => 'admin/index/update'
                    ,'paixu'  => 5
                    ,'pid' => 601
                ],
                ['id' => 60106
                    ,'title' => '查看'
                    ,'name' => 'admin/admininfo/readadmin'
                    ,'paixu' => 6
                    ,'pid' => 601
                ],
                ['id' => 60107
                    ,'title' => '状态'
                    ,'name' => 'admin/index/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 601
                ],
                ['id' => 60108
                    ,'title' => '重置密码'
                    ,'name' => 'admin/index/resetpassword'
                    ,'paixu'  => 8
                    ,'pid' => 601
                ],
                ['id' => 60109
                    ,'title' => '下载模板'
                    ,'name' => 'admin/index/downloadxls'
                    ,'paixu'  => 9
                    ,'pid' => 601
                ],
                ['id' => 60110
                    ,'title' => '批量上传'
                    ,'name' => 'admin/index/createall'
                    ,'paixu' => 9
                    ,'pid' => 601
                ],
                ['id' => 60111
                    ,'title' => '批量保存'
                    ,'name' => 'admin/index/saveall'
                    ,'paixu' => 10
                    ,'pid' => 601
                ],
            ['id' => 602
                ,'title' => '权限列表'
                ,'name' => 'admin/authrule/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 6
                ,'url' => '/admin/authrule'
            ],
                ['id' => 60201
                    ,'title' => '添加'
                    ,'name' => 'admin/authrule/create'
                    ,'paixu'  => 1
                    ,'pid' => 602
                ],
                ['id' => 60202
                    ,'title' => '保存'
                    ,'name' => 'admin/authrule/save'
                    ,'paixu'  => 2
                    ,'pid' => 602
                ],
                ['id' => 60203
                    ,'title' => '删除'
                    ,'name' => 'admin/authrule/delete'
                    ,'paixu' => 3
                    ,'pid' => 602
                ],
                ['id' => 60204
                    ,'title' => '编辑'
                    ,'name' => 'admin/authrule/edit'
                    ,'paixu' => 4
                    ,'pid' => 602
                ],
                ['id' => 60205
                    ,'title' => '更新'
                    ,'name' => 'admin/authrule/update'
                    ,'paixu'  => 5
                    ,'pid' => 602
                ],
                ['id' => 60206
                    ,'title' => '查看'
                    ,'name' => 'admin/authrule/read'
                    ,'paixu' => 6
                    ,'pid' => 602
                ],
                ['id' => 60207
                    ,'title' => '状态'
                    ,'name' => 'admin/authrule/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 602
                ],

            ['id' => 603
                ,'title' => '角色列表'
                ,'name' => 'admin/authgroup/index'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 6
                ,'url' => '/admin/authgroup'
            ],
                ['id' => 60301
                    ,'title' => '添加'
                    ,'name' => 'admin/authgroup/create'
                    ,'paixu'  => 1
                    ,'pid' => 603
                ],
                ['id' => 60302
                    ,'title' => '保存'
                    ,'name' => 'admin/authgroup/save'
                    ,'paixu'  => 2
                    ,'pid' => 603
                ],
                ['id' => 60303
                    ,'title' => '删除'
                    ,'name' => 'admin/authgroup/delete'
                    ,'paixu' => 3
                    ,'pid' => 603
                ],
                ['id' => 60304
                    ,'title' => '编辑'
                    ,'name' => 'admin/authgroup/edit'
                    ,'paixu' => 4
                    ,'pid' => 603
                ],
                ['id' => 60305
                    ,'title' => '更新'
                    ,'name' => 'admin/authgroup/update'
                    ,'paixu'  => 5
                    ,'pid' => 603
                ],
                ['id' => 60306
                    ,'title' => '查看'
                    ,'name' => 'admin/authgroup/read'
                    ,'paixu' => 6
                    ,'pid' => 603
                ],
                ['id' => 60307
                    ,'title' => '状态'
                    ,'name' => 'admin/authgroup/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 603
                ],
            ['id' => 604
                ,'title' => '角色用户对应表'
                ,'name' => 'admin/authgroupaccess/index'
                ,'paixu' => 2
                ,'ismenu'  => 0
                ,'pid'  => 6
                ,'url' => '/admin/authgroupaccess'
            ],
                ['id' => 60401
                    ,'title' => '添加'
                    ,'name' => 'admin/authgroupaccess/create'
                    ,'paixu'  => 1
                    ,'pid' => 604
                ],
                ['id' => 60402
                    ,'title' => '保存'
                    ,'name' => 'admin/authgroupaccess/save'
                    ,'paixu'  => 2
                    ,'pid' => 604
                ],
                ['id' => 60403
                    ,'title' => '删除'
                    ,'name' => 'admin/authgroupaccess/delete'
                    ,'paixu' => 3
                    ,'pid' => 604
                ],
            /*======================================================================
             * 考试管理
             */
            ['id' => 7
                ,'title' => '系统管理'
                ,'name'  => 'system'
                ,'paixu'  => 10
                ,'ismenu'  => 1
                ,'font'  => '&#xe6ae;'
            ],
            /*====================================*/
            ['id' => 701
                ,'title' => '类别管理'
                ,'name' => 'system/category/index'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/system/category'
            ],
                ['id' => 70101
                    ,'title' => '添加'
                    ,'name' => 'system/category/create'
                    ,'paixu'  => 1
                    ,'pid' => 701
                ],
                ['id' => 70102
                    ,'title' => '保存'
                    ,'name' => 'system/category/save'
                    ,'paixu'  => 2
                    ,'pid' => 701
                ],
                ['id' => 70103
                    ,'title' => '删除'
                    ,'name' => 'system/category/delete'
                    ,'paixu' => 3
                    ,'pid' => 701
                ],
                ['id' => 70104
                    ,'title' => '编辑'
                    ,'name' => 'system/category/edit'
                    ,'paixu' => 4
                    ,'pid' => 701
                ],
                ['id' => 70105
                    ,'title' => '更新'
                    ,'name' => 'system/category/update'
                    ,'paixu'  => 5
                    ,'pid' => 701
                ],
                ['id' => 70106
                    ,'title' => '查看'
                    ,'name' => 'system/category/read'
                    ,'paixu' => 6
                    ,'pid' => 701
                ],
                ['id' => 70107
                    ,'title' => '状态'
                    ,'name' => 'system/category/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 701
                ],
            ['id' => 702
                ,'title' => '单位管理'
                ,'name' => 'system/school/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/system/school'
            ],
                ['id' => 70201
                    ,'title' => '添加'
                    ,'name' => 'system/school/create'
                    ,'paixu'  => 1
                    ,'pid' => 702
                ],
                ['id' => 70202
                    ,'title' => '保存'
                    ,'name' => 'system/school/save'
                    ,'paixu'  => 2
                    ,'pid' => 702
                ],
                ['id' => 70203
                    ,'title' => '删除'
                    ,'name' => 'system/school/delete'
                    ,'paixu' => 3
                    ,'pid' => 702
                ],
                ['id' => 70204
                    ,'title' => '编辑'
                    ,'name' => 'system/school/edit'
                    ,'paixu' => 4
                    ,'pid' => 702
                ],
                ['id' => 70205
                    ,'title' => '更新'
                    ,'name' => 'system/school/update'
                    ,'paixu'  => 5
                    ,'pid' => 702
                ],
                ['id' => 70206
                    ,'title' => '查看'
                    ,'name' => 'system/school/read'
                    ,'paixu' => 6
                    ,'pid' => 702
                ],
                ['id' => 70207
                    ,'title' => '状态'
                    ,'name' => 'system/school/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 702
                ],
                ['id' => 70208
                    ,'title' => '能否组织考试'
                    ,'name' => 'system/school/setkaoshi'
                    ,'paixu' => 8
                    ,'pid' => 702
                ],
            ['id' => 703
                ,'title' => '文件管理'
                ,'name' => 'system/fields/index'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/system/file'
            ],
                ['id' => 70301
                    ,'title' => '删除'
                    ,'name' => 'system/fields/delete'
                    ,'paixu' => 1
                    ,'pid' => 703
                ],
                ['id' => 70302
                    ,'title' => '下载'
                    ,'name' => 'system/fields/download'
                    ,'paixu' => 2
                    ,'pid' => 703
                ],
            ['id' => 704
                ,'title' => '数据备份'
                ,'name' => 'system/backup/index'
                ,'paixu' => 6
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/system/backup/index'
            ],
                ['id' => 70401
                    ,'title' => '创建'
                    ,'name' => 'system/backup/export'
                    ,'paixu' => 1
                    ,'pid'  => 704
                ],
                ['id' => 70402
                    ,'title' => '还原'
                    ,'name' => 'system/backup/import'
                    ,'paixu' => 2
                    ,'pid'  => 704
                ],
                ['id' => 70403
                    ,'title' => '下载'
                    ,'name' => 'system/backup/down'
                    ,'paixu' => 3
                    ,'pid'  => 704
                ],
                ['id' => 70404
                    ,'title' => '删除'
                    ,'name' => 'system/backup/delete'
                    ,'paixu' => 4
                    ,'pid'  => 704
                ],

            ['id' => 705
                ,'title' => '系统设置'
                ,'name' => 'system/systembase/edit'
                ,'paixu' => 7
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/system/'
            ],
                ['id' => 70501
                    ,'title' => '更新'
                    ,'name' => 'system/systembase/update'
                    ,'paixu' => 1
                    ,'pid'  => 705
                ],
                // ['id' => 70502
                //     ,'title' => '初始化'
                //     ,'name' => 'system/systembase/resetmayi'
                //     ,'paixu' => 2
                //     ,'pid'  => 705
                // ],
            ['id' => 706
                ,'title' => '统计项目'
                ,'name' => 'kaoshi/tongjixiangmu/index'
                ,'paixu' => 5
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/kaoshi/tjxm/index'
            ],
                ['id' => 70601
                    ,'title' => '添加'
                    ,'name' => 'kaoshi/tongjixiangmu/create'
                    ,'paixu' => 1
                    ,'pid'  => 706
                ],
                ['id' => 70602
                    ,'title' => '保存'
                    ,'name' => 'kaoshi/tongjixiangmu/save'
                    ,'paixu' => 2
                    ,'pid'  => 706
                ],
                ['id' => 70603
                    ,'title' => '编辑'
                    ,'name' => 'kaoshi/tongjixiangmu/edit'
                    ,'paixu' => 3
                    ,'pid'  => 706
                ],
                ['id' => 70604
                    ,'title' => '更新'
                    ,'name' => 'kaoshi/tongjixiangmu/update'
                    ,'paixu' => 4
                    ,'pid'  => 706
                ],
                ['id' => 70605
                    ,'title' => '参与统计'
                    ,'name' => 'kaoshi/tongjixiangmu/settongji'
                    ,'paixu' => 5
                    ,'pid'  => 706
                ],
                ['id' => 70606
                    ,'title' => '添加'
                    ,'name' => 'kaoshi/tongjixiangmu/setstatus'
                    ,'paixu' => 6
                    ,'pid'  => 706
                ],
                ['id' => 70607
                    ,'title' => '删除'
                    ,'name' => 'kaoshi/tongjixiangmu/delete'
                    ,'paixu' => 7
                    ,'pid'  => 706
                ],
            ['id' => 707
                ,'title' => '教室管理'
                ,'name' => 'system/classroom/index'
                ,'paixu' => 4
                ,'ismenu'  => 1
                ,'pid'  => 7
                ,'url' => '/system/classroom'
            ],
                ['id' => 70701
                    ,'title' => '添加'
                    ,'name' => 'system/classroom/create'
                    ,'paixu'  => 1
                    ,'pid' => 707
                ],
                ['id' => 70702
                    ,'title' => '保存'
                    ,'name' => 'system/classroom/save'
                    ,'paixu'  => 2
                    ,'pid' => 707
                ],
                ['id' => 70703
                    ,'title' => '删除'
                    ,'name' => 'system/classroom/delete'
                    ,'paixu' => 3
                    ,'pid' => 707
                ],
                ['id' => 70704
                    ,'title' => '编辑'
                    ,'name' => 'system/classroom/edit'
                    ,'paixu' => 4
                    ,'pid' => 707
                ],
                ['id' => 70705
                    ,'title' => '更新'
                    ,'name' => 'system/classroom/update'
                    ,'paixu'  => 5
                    ,'pid' => 707
                ],
                ['id' => 70706
                    ,'title' => '查看'
                    ,'name' => 'system/classroom/read'
                    ,'paixu' => 6
                    ,'pid' => 707
                ],
                ['id' => 70707
                    ,'title' => '状态'
                    ,'name' => 'system/classroom/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 707
                ],


            /*======================================================================
             * 考试管理
             */
            ['id' => 8
                ,'title' => '荣誉管理'
                ,'name'  => 'rongyu'
                ,'paixu'  => 5
                ,'ismenu'  => 1
                ,'font'  => '&#xe6e4;'
                ,'status' => 0
            ],
            /*====================================*/
            ['id' => 801
                ,'title' => '单位荣誉'
                ,'name' => 'rongyu/danwei/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 8
                ,'url' => '/rongyu/danwei'
            ],
                ['id' => 80101
                    ,'title' => '添加'
                    ,'name' => 'rongyu/danwei/create'
                    ,'paixu'  => 1
                    ,'pid' => 801
                ],
                ['id' => 80102
                    ,'title' => '保存'
                    ,'name' => 'rongyu/danwei/save'
                    ,'paixu'  => 2
                    ,'pid' => 801
                ],
                ['id' => 80103
                    ,'title' => '删除'
                    ,'name' => 'rongyu/danwei/delete'
                    ,'paixu' => 3
                    ,'pid' => 801
                ],
                ['id' => 80104
                    ,'title' => '编辑'
                    ,'name' => 'rongyu/danwei/edit'
                    ,'paixu' => 4
                    ,'pid' => 801
                ],
                ['id' => 80105
                    ,'title' => '更新'
                    ,'name' => 'rongyu/danwei/update'
                    ,'paixu'  => 5
                    ,'pid' => 801
                ],
                ['id' => 80106
                    ,'title' => '查看'
                    ,'name' => 'rongyu/danwei/read'
                    ,'paixu' => 6
                    ,'pid' => 801
                ],
                ['id' => 80107
                    ,'title' => '状态'
                    ,'name' => 'rongyu/danwei/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 801
                ]
                ,['title' => '批量上传'
                    ,'id' => 80108
                    ,'name' => 'rongyu/danwei/createall'
                    ,'paixu' => 8
                    ,'pid' => 801
                ],
                ['id' => 80109
                    ,'title' => '批量保存'
                    ,'name' => 'rongyu/danwei/saveall'
                    ,'paixu' => 9
                    ,'pid' => 801
                ],
            ['id' => 802
                ,'title' => '教师荣誉册'
                ,'name' => 'rongyu/jiaoshi/index'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 8
                ,'url' => '/rongyu/jiaoshi'
            ],
                ['id' => 80201
                    ,'title' => '添加'
                    ,'name' => 'rongyu/jiaoshi/create'
                    ,'paixu'  => 1
                    ,'pid' => 802
                ],
                ['id' => 80202
                    ,'title' => '保存'
                    ,'name' => 'rongyu/jiaoshi/save'
                    ,'paixu'  => 2
                    ,'pid' => 802
                ],
                ['id' => 80203
                    ,'title' => '删除'
                    ,'name' => 'rongyu/jiaoshi/delete'
                    ,'paixu' => 3
                    ,'pid' => 802
                ],
                ['id' => 80204
                    ,'title' => '编辑'
                    ,'name' => 'rongyu/jiaoshi/edit'
                    ,'paixu' => 4
                    ,'pid' => 802
                ],
                ['id' => 80205
                    ,'title' => '更新'
                    ,'name' => 'rongyu/jiaoshi/update'
                    ,'paixu'  => 5
                    ,'pid' => 802
                ],
                ['id' => 80206
                    ,'title' => '查看'
                    ,'name' => 'rongyu/jiaoshi/read'
                    ,'paixu' => 6
                    ,'pid' => 802
                ],
                ['id' => 80207
                    ,'title' => '状态'
                    ,'name' => 'rongyu/jiaoshi/setstatus'
                    ,'paixu' => 6
                    ,'pid' => 802
                ],
                ['id' => 80208
                    ,'title' => '查看荣誉信息'
                    ,'name' => 'rongyu/jsrongyuinfo/rongyulist'
                    ,'paixu' => 7
                    ,'pid' => 802
                ],
                ['id' => 80209
                    ,'title' => '下载表格'
                    ,'name' => 'rongyu/jsrongyuinfo/outxlsx'
                    ,'paixu' => 8
                    ,'pid' => 802
                ],
            ['id' => 803
                ,'title' => '教师荣誉信息'
                ,'name' => 'rongyu/jsrongyuinfo/index'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 8
                ,'url' => '/rongyu/jsryinfo'
            ],
                ['id' => 80301
                    ,'title' => '添加'
                    ,'name' => 'rongyu/jsrongyuinfo/create'
                    ,'paixu'  => 1
                    ,'pid' => 803
                ],
                ['id' => 80302
                    ,'title' => '保存'
                    ,'name' => 'rongyu/jsrongyuinfo/save'
                    ,'paixu'  => 2
                    ,'pid' => 803
                ],
                ['id' => 80303
                    ,'title' => '删除'
                    ,'name' => 'rongyu/jsrongyuinfo/delete'
                    ,'paixu' => 3
                    ,'pid' => 803
                ],
                ['id' => 80304
                    ,'title' => '编辑'
                    ,'name' => 'rongyu/jsrongyuinfo/edit'
                    ,'paixu' => 4
                    ,'pid' => 803
                ],
                ['id' => 80305
                    ,'title' => '更新'
                    ,'name' => 'rongyu/jsrongyuinfo/update'
                    ,'paixu'  => 5
                    ,'pid' => 803
                ],
                ['id' => 80306
                    ,'title' => '查看'
                    ,'name' => 'rongyu/jsrongyuinfo/read'
                    ,'paixu' => 6
                    ,'pid' => 803
                ],
                ['id' => 80307
                    ,'title' => '状态'
                    ,'name' => 'rongyu/jsrongyuinfo/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 803
                ],
                ['id' => 80308
                    ,'title' => '批量上传'
                    ,'name' => 'rongyu/jsrongyuinfo/createall'
                    ,'paixu' => 8
                    ,'pid' => 803
                ],
                ['id' => 80309
                    ,'title' => '批量保存'
                    ,'name' => 'rongyu/jsrongyuinfo/saveall'
                    ,'paixu' => 9
                    ,'pid' => 803
                ],
            ['id' => 804
                ,'title' => '荣誉统计'
                ,'name' => 'rongyu/tongji/index'
                ,'paixu' => 4
                ,'ismenu'  => 1
                ,'pid'  => 8
                ,'url' => '/rongyu/tongji'
            ],


            /*======================================================================
             * 考试管理
             */
            ['id' => 9
                ,'title' => '课题管理'
                ,'name'  => 'keti'
                ,'paixu'  => 6
                ,'ismenu'  => 1
                ,'font'  => '&#xe6b3;'
                ,'status' => 0
            ],
            /*====================================*/
            ['id' => 901
                ,'title' => '立项列表'
                ,'name' => 'keti/lixiang/index'
                ,'paixu' => 1
                ,'ismenu'  => 1
                ,'pid'  => 9
                ,'url' => '/keti/lixiang'
            ],
                ['id' => 90101
                    ,'title' => '添加'
                    ,'name' => 'keti/lixiang/create'
                    ,'paixu'  => 1
                    ,'pid' => 901
                ],
                ['id' => 90102
                    ,'title' => '保存'
                    ,'name' => 'keti/lixiang/save'
                    ,'paixu'  => 2
                    ,'pid' => 901
                ],
                ['id' => 90103
                    ,'title' => '删除'
                    ,'name' => 'keti/lixiang/delete'
                    ,'paixu' => 3
                    ,'pid' => 901
                ],
                ['id' => 90104
                    ,'title' => '编辑'
                    ,'name' => 'keti/lixiang/edit'
                    ,'paixu' => 4
                    ,'pid' => 901
                ],
                ['id' => 90105
                    ,'title' => '更新'
                    ,'name' => 'keti/lixiang/update'
                    ,'paixu'  => 5
                    ,'pid' => 901
                ],
                ['id' => 90106
                    ,'title' => '课题列表'
                    ,'name' => 'keti/lixiang/list'
                    ,'paixu' => 6
                    ,'pid' => 901
                ],
                ['id' => 90107
                    ,'title' => '状态'
                    ,'name' => 'keti/lixiang/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 901
                ],
                ['id' => 90108
                    ,'title' => '查看课题信息'
                    ,'name' => 'keti/ketiinfo/ketilist'
                    ,'paixu' => 8
                    ,'pid' => 901
                ],
                ['id' => 90109
                    ,'title' => '下载'
                    ,'name' => 'keti/lixiang/outxlsx'
                    ,'paixu' => 9
                    ,'pid' => 901
                ],
            ['id' => 902
                ,'title' => '课题列表'
                ,'name' => 'keti/ketiinfo/index'
                ,'paixu' => 3
                ,'ismenu'  => 1
                ,'pid'  => 9
                ,'url' => '/keti/info'
            ],
                // 课题信息
                ['id' => 90201
                    ,'title' => '添加'
                    ,'name' => 'keti/ketiinfo/create'
                    ,'paixu'  => 1
                    ,'pid' => 902
                ],
                ['id' => 90202
                    ,'title' => '保存'
                    ,'name' => 'keti/ketiinfo/save'
                    ,'paixu'  => 2
                    ,'pid' => 902
                ],
                ['id' => 90203
                    ,'title' => '删除'
                    ,'name' => 'keti/ketiinfo/delete'
                    ,'paixu' => 3
                    ,'pid' => 902
                ],
                ['id' => 90204
                    ,'title' => '编辑'
                    ,'name' => 'keti/ketiinfo/edit'
                    ,'paixu' => 4
                    ,'pid' => 902
                ],
                ['id' => 90205
                    ,'title' => '更新'
                    ,'name' => 'keti/ketiinfo/update'
                    ,'paixu'  => 5
                    ,'pid' => 902
                ],
                ['id' => 90206
                    ,'title' => '查看'
                    ,'name' => 'keti/ketiinfo/read'
                    ,'paixu' => 6
                    ,'pid' => 902
                ],
                ['id' => 90207
                    ,'title' => '状态'
                    ,'name' => 'keti/ketiinfo/setstatus'
                    ,'paixu' => 7
                    ,'pid' => 902
                ],
                ['id' => 90208
                    ,'title' => '批量上传'
                    ,'name' => 'keti/ketiinfo/createall'
                    ,'paixu' => 8
                    ,'pid' => 902
                ],
                ['id' => 90209
                    ,'title' => '批量保存'
                    ,'name' => 'keti/ketiinfo/saveall'
                    ,'paixu' => 9
                    ,'pid' => 902
                ],
                ['id' => 90210
                    ,'title' => '结题编辑'
                    ,'name' => 'keti/ketiinfo/jieti'
                    ,'paixu' => 10
                    ,'pid' => 902
                ],
                ['id' => 90211
                    ,'title' => '结题更新'
                    ,'name' => 'keti/ketiinfo/jtupdate'
                    ,'paixu' => 11
                    ,'pid' => 902
                ],
                ['id' => 90212
                    ,'title' => '结题删除'
                    ,'name' => 'keti/ketiinfo/deletejieti'
                    ,'paixu' => 12
                    ,'pid' => 902
                ],
                ['id' => 200000000
                    ,'title' => '新建权限起始位置'
                    ,'name' => 'zhanwei_200000000'
                    ,'paixu' => 12
                    ,'pid' => 902
                    ,'status' => 0
                ],
            ['id' => 903
                ,'title' => '结题列表'
                ,'name' => 'keti/jieti/index'
                ,'paixu' => 2
                ,'ismenu'  => 1
                ,'pid'  => 9
                ,'url' => '/keti/jieti'
            ],
                ['id' => 90301
                    ,'title' => '添加'
                    ,'name' => 'keti/jieti/create'
                    ,'paixu'  => 1
                    ,'pid' => 903
                ],
                ['id' => 90302
                    ,'title' => '保存'
                    ,'name' => 'keti/jieti/save'
                    ,'paixu'  => 2
                    ,'pid' => 903
                ],
                ['id' => 90303
                    ,'title' => '编辑'
                    ,'name' => 'keti/jieti/edit'
                    ,'paixu'  => 3
                    ,'pid' => 903
                ],
                ['id' => 90304
                    ,'title' => '更新'
                    ,'name' => 'keti/jieti/update'
                    ,'paixu'  => 4
                    ,'pid' => 903
                ],
                ['id' => 90305
                    ,'title' => '删除'
                    ,'name' => 'keti/jieti/delete'
                    ,'paixu'  => 5
                    ,'pid' => 903
                ],
                ['id' => 90306
                    ,'title' => '录入结题'
                    ,'name' => 'keti/jieti/jieti'
                    ,'paixu'  => 6
                    ,'pid' => 903
                ],
                ['id' => 90307
                    ,'title' => '录入结题'
                    ,'name' => 'keti/jieti/jtupdate'
                    ,'paixu'  => 7
                    ,'pid' => 903
                ],
                ['id' => 90308
                    ,'title' => '课题列表'
                    ,'name' => 'keti/jieti/list'
                    ,'paixu'  => 8
                    ,'pid' => 903
                ],
            ['id' => 904
                ,'title' => '课题统计'
                ,'name' => 'keti/tongji/index'
                ,'paixu' => 4
                ,'ismenu'  => 1
                ,'pid'  => 9
                ,'url' => '/keti/tongji'
            ],
        ];

        $this->execute('DELETE FROM cj_auth_rule where id>0');

        // 保存数据
        $this->table('auth_rule')->insert($rows)->save();
    }
}
