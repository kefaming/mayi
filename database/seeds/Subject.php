<?php

use think\migration\Seeder;

class Subject extends Seeder
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
        $rows= [
            ['id'=>101
                ,'title' => '语文'
                ,'jiancheng' => '语'
                ,'category_id' => 11001
                ,'lieming' => 'yuwen'
                ,'kaoshi' => 1
                ,'paixu' => 1
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>102
                ,'title' => '数学'
                ,'jiancheng' => '数'
                ,'category_id' => 11002
                ,'lieming' => 'shuxue'
                ,'kaoshi' => 1
                ,'paixu' => 2
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>103
                ,'title' => '外语'
                ,'jiancheng' => '外'
                ,'category_id' => 11003
                ,'lieming' => 'waiyu'
                ,'kaoshi' => 1
                ,'paixu' => 3
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>104
                ,'title' => '体育与健康'
                ,'jiancheng' => '体'
                ,'category_id' => 11007
                ,'lieming' => 'tiyu'
                ,'paixu' => 4
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>105
                ,'title' => '科学'
                ,'jiancheng' => '科'
                ,'category_id' => 11006
                ,'lieming' => 'kexue'
                ,'paixu' => 5
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>106
                ,'title' => '生物'
                ,'jiancheng' => '生'
                ,'category_id' => 11006
                ,'lieming' => 'shengwu'
                ,'paixu' => 6
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>107
                ,'title' => '物理'
                ,'jiancheng' => '理'
                ,'category_id' => 11006
                ,'lieming' => 'wuli'
                ,'kaoshi' => 0
                ,'paixu' => 7
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>108
                ,'title' => '化学'
                ,'jiancheng' => '化'
                ,'category_id' => 11006
                ,'lieming' => 'huaxue'
                ,'kaoshi' => 0
                ,'paixu' => 8
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>109
                ,'title' => '音乐'
                ,'jiancheng' => '音'
                ,'category_id' => 11008
                ,'lieming' => 'yinyue'
                ,'paixu' => 9
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>110
                ,'title' => '美术'
                ,'jiancheng' => '美'
                ,'category_id' => 11008
                ,'lieming' => 'meishu'
                ,'paixu' => 10
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>111
                ,'title' => '信息技术'
                ,'jiancheng' => '信息'
                ,'category_id' => 11009
                ,'lieming' => 'xinxi'
                ,'paixu' => 11
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>112
                ,'title' => '研究性学习/社区服务、实践'
                ,'jiancheng' => '社区'
                ,'lieming' => 'shequ'
                ,'category_id' => 11009
                ,'paixu' => 12
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>113
                ,'title' => '劳动与技术'
                ,'jiancheng' => '劳动'
                ,'category_id' => 11009
                ,'lieming' => 'laodong'
                ,'paixu' => 13
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>114
                ,'title' => '品德与生活/社会'
                ,'jiancheng' => '品德'
                ,'category_id' => 11004
                ,'lieming' => 'pinshe'
                ,'paixu' => 14
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>115
                ,'title' => '思想品德'
                ,'jiancheng' => '品社'
                ,'category_id' => 11004
                ,'lieming' => 'sixiang'
                ,'paixu' => 15
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>116
                ,'title' => '历史'
                ,'jiancheng' => '史'
                ,'category_id' => 11005
                ,'lieming' => 'lishi'
                ,'paixu' => 16
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>117
                ,'title' => '地理'
                ,'jiancheng' => '地理'
                ,'category_id' => 11005
                ,'lieming' => 'dili'
                ,'paixu' => 17
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>118
                ,'title' => '地方课程'
                ,'jiancheng' => '地方'
                ,'lieming' => 'difang'
                ,'category_id' => 11010
                ,'paixu' => 18
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>119
                ,'title' => '校本课程'
                ,'jiancheng' => '校本'
                ,'lieming' => 'xiaoben'
                ,'category_id' => 11010
                ,'paixu' => 19
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>120
                ,'title' => '幼儿园全科'
                ,'jiancheng' => '幼儿园'
                ,'lieming' => 'youeryuan'
                ,'category_id' => 11011
                ,'paixu' => 20
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>121
                ,'title' => '德育'
                ,'jiancheng' => '德'
                ,'lieming' => 'deyu'
                ,'category_id' => 11010
                ,'paixu' => 21
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>122
                ,'title' => '心理健康'
                ,'jiancheng' => '心'
                ,'lieming' => 'xinli'
                ,'category_id' => 11010
                ,'paixu' => 22
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>123
                ,'title' => '写字'
                ,'jiancheng' => '写'
                ,'lieming' => 'xiezi'
                ,'category_id' => 11010
                ,'paixu' => 23
                ,'create_time' => time()
                ,'update_time' => time()
            ],
            ['id'=>124
                ,'title' => '其他'
                ,'jiancheng' => '其他'
                ,'lieming' => 'qita'
                ,'category_id' => 11012
                ,'paixu' => 100
                ,'create_time' => time()
                ,'update_time' => time()
            ],

        ];

        $serRows = $this->fetchAll('select * from cj_subject');
        if(is_array($serRows) && count($serRows) > 0)
        {
            $rows = [];
            return true;
        }

        // 保存数据
        $this->table('subject')->insert($rows)->save();
    }
}
