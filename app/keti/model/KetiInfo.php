<?php

namespace app\Keti\model;

// 引用数据模型基类
use app\BaseModel;

class KetiInfo extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'lixiang_id' => 'int'
        ,'jieti_id' => 'int'
        ,'bianhao' => 'varchar'
        ,'lxpic' => 'varchar'
        ,'subject_id' => 'int'
        ,'fzdanwei_id' => 'int'
        ,'category_id' => 'int'
        ,'jhjtshijian' => 'int'
        ,'jtshijian' => 'int'
        ,'jddengji_id' => 'int'
        ,'jtpic' => 'varchar'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'status' => 'tinyint'
        ,'beizhu' => 'varchar'
    ];


    //搜索课题册
    public function search($srcfrom)
    {
    	$src = [
            'lxdanwei_id' => array()
            ,'lxcategory_id' => array()
            ,'fzdanwei_id' => array()
            ,'subject_id' => array()
            ,'category_id' => array()
            ,'jddengji_id' => array()
            ,'lixiang_id' => ''
            ,'jieti_id' => ''
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['lxdanwei_id'] = str_to_array($src['lxdanwei_id']);
        $src['lxcategory_id'] = str_to_array($src['lxcategory_id']);
        $src['fzdanwei_id'] = str_to_array($src['fzdanwei_id']);
        $src['subject_id'] = str_to_array($src['subject_id']);
        $src['category_id'] = str_to_array($src['category_id']);
        $src['jddengji_id'] = str_to_array($src['jddengji_id']);

    	$data = $this
    		->when(count($src['lxdanwei_id']) > 0, function($query) use($src){
                	$query->where('lixiang_id', 'in', function ($q) use($src){
                        $q->name('lixiang')->where('lixiang_id', 'in', $src['lxdanwei_id'])->field('id');
                    });
                })
    		->when(count($src['lxcategory_id']) > 0, function($query) use($src){
                	$query->where('lixiang_id', 'in', function($q) use($src){
                        $q->name('lixiang')->where('category_id', 'in', $src['lxcategory_id'])->field('id');
                    });
                })
    		->when(count($src['fzdanwei_id']) > 0, function($query) use($src){
                	$query->where('fzdanwei_id', 'in', $src['fzdanwei_id']);
                })
            ->when(count($src['subject_id']) > 0, function($query) use($src){
                    $query->where('subject_id', 'in', $src['subject_id']);
                })
            ->when(count($src['category_id']) > 0, function($query) use($src){
                    $query->where('category_id', 'in', $src['category_id']);
                })
            ->when(count($src['jddengji_id']) > 0, function($query) use($src){
                    $query->where('jddengji_id', 'in', $src['jddengji_id']);
                })
            ->when(strlen($src['lixiang_id']) > 0, function($query) use($src){
                    $query->where('lixiang_id', $src['lixiang_id']);
                })
            ->when(strlen($src['jieti_id']) > 0, function($query) use($src){
                    $query->where('jieti_id', $src['jieti_id']);
                })
    		->when(strlen($src['searchval']) > 0, function($query) use($src){
                	$query->where('title|bianhao', 'like', '%' . $src['searchval'] . '%');
                })
            ->with(
                [
                    'fzSchool' => function($query){
                        $query->field('id, jiancheng');
                    },
                    'glLixiang' => function($query){
                        $query->field('id, lxdanwei_id, category_id, lxshijian')
                            ->with([
                                'ktCategory' => function($q){
                                    $q->field('id, title');
                                },
                                'ktLxdanwei' => function($q){
                                    $q->field('id, jiancheng');
                                }
                            ]);
                    },
                    'ktCategory' => function($query){
                        $query->field('id, title');
                    },
                    'ktSubject' => function($query){
                        $query->field('id, title');
                    },
                    'ktZcr' => function($query){
                        $query->field('ketiinfo_id, teacher_id')
                            ->with([
                                'teacher' => function($q){
                                    $q->field('id, xingming');
                                }
                            ]);
                    },
                    'ktCy' => function($query){
                        $query->field('ketiinfo_id, teacher_id')
                            ->with([
                                'teacher' => function($q){
                                    $q->field('id, xingming');
                                }
                            ]);
                    }
                    ,'ktJdDengji' => function($query){
                        $query->field('id, title');
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


    // 根据时间搜索立项课题
    public function searchLxSj($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'betweentime' => date('Y').'-01-01～' . date('Y-m-d')
        ];

        $src = array_cover($srcfrom, $src);
        $src['time'] = explode('～', $src['betweentime']);

        $data = $this
            ->where('lixiang_id', 'in', function($query) use($src){
                $query->name('lixiang')
                    ->whereBetweenTime('lxshijian', $src['time'][0], $src['time'][1])
                    ->where('status', 1)
                    ->field('id');
            })
            ->field('id, title, lixiang_id, bianhao, subject_id, fzdanwei_id, category_id')
            ->with([
                'fzSchool' => function($query){
                    $query->field('id, jiancheng');
                },
                'glLixiang' => function($query){
                    $query->field('id, lxdanwei_id, category_id, lxshijian')
                        ->with([
                            'ktCategory' => function($q){
                                $q->field('id, title');
                            },
                            'ktLxdanwei' => function($q){
                                $q->field('id, jiancheng, jibie_id');
                            }
                        ]);
                },
                'ktCategory' => function($query){
                    $query->field('id, title');
                },
                'ktSubject' => function($query){
                    $query->field('id, title');
                },
            ])
            ->where('status', 1)
            ->select();
        return $data;
    }


    // 根据时间搜索结题课题
    public function searchJtSj($srcfrom)
    {
        $src = [
            'searchval' => ''
            ,'betweentime' => date('Y').'-01-01～' . date('Y-m-d')
        ];

        $src = array_cover($srcfrom, $src);
        $src['time'] = explode('～', $src['betweentime']);

        $data = $this
            ->where('lixiang_id', 'in', function($query) use($src){
                $query->name('jieti')
                    ->whereBetweenTime('shijian', $src['time'][0], $src['time'][1])
                    ->where('status', 1)
                    ->field('id');
            })
            ->where('jddengji_id', 'in', '11802, 11803')
            ->field('id, title, lixiang_id, bianhao, subject_id, fzdanwei_id, category_id')
            ->with([
                'fzSchool' => function($query){
                    $query->field('id, jiancheng');
                },
                'glJieti' => function($query){
                    $query->field('id, danwei_id, shijian')
                        ->with([
                            'glDanwei' => function($query){
                                $query->field('id, title, jibie_id');
                            },
                        ]);
                },
                'ktCategory' => function($query){
                    $query->field('id, title');
                },
                'ktSubject' => function($query){
                    $query->field('id, title');
                },
            ])
            ->where('status', 1)
            ->select();
        return $data;
    }


    // 搜索教师参与的课题
    public function srcTeacherKeti($teacher_id)
    {
        // 查询信息
        $data = self::where('status', 1)
            ->where('id', 'in', function($query) use($teacher_id){
                $query->name('KetiCanyu')
                    ->where('teacher_id', $teacher_id)
                    ->field('ketiinfo_id');
            })
            ->with(
            [
                'fzSchool' => function($query){
                    $query->field('id, jiancheng, jibie_id')
                        ->with(['dwJibie' => function($q){
                            $q->field('id, title');
                        }]);
                },
                'ktCategory' => function($query){
                    $query->field('id, title');
                },
                'ktZcr' => function($query){
                    $query->field('ketiinfo_id, teacher_id')
                        ->with([
                            'teacher' => function($q){
                                $q->field('id, xingming');
                            }
                        ]);
                },
                'ktJdDengji' => function($query){
                    $query->field('id, title');
                },
                'glLixiang' => function($query){
                    $query->field('id, lxdanwei_id, category_id, lxshijian')
                        ->with([
                            'ktCategory' => function($q){
                                $q->field('id, title');
                            },
                            'ktLxdanwei' => function($q){
                                $q->field('id, jiancheng, title, jibie_id')
                                    ->with(['dwJibie' => function($q){
                                        $q->field('id, title');
                                    }]);
                            }
                        ]);
                },
            ])
            ->field('id, title, fzdanwei_id, category_id, jddengji_id, bianhao, lixiang_id')
            ->order('id')
            ->select();

        return $data;
    }


    //搜索课题册
    public function srcLixiangCe($lixiang_id)
    {
        $data = $this
            ->where('lixiang_id', $lixiang_id)
            ->with(
                [
                    'fzSchool' => function($query){
                        $query->field('id, jiancheng');
                    },
                    'glLixiang' => function($query){
                        $query->field('id, title, lxdanwei_id, category_id, lxshijian')
                            ->with([
                                'ktCategory' => function($q){
                                    $q->field('id, title');
                                },
                                'ktLxdanwei' => function($q){
                                    $q->field('id, jiancheng, title, jibie_id');
                                }
                            ]);
                    },
                    'ktCategory'=>function($query){
                        $query->field('id, title');
                    },
                    'ktSubject'=>function($query){
                        $query->field('id, title');
                    },
                    'ktZcr'=>function($query){
                        $query->field('ketiinfo_id, teacher_id')
                            ->with([
                                'teacher'=>function($q){
                                    $q->field('id, xingming');
                                }
                            ]);
                    }
                ]
            )
            ->select();

        return $data;
    }


    //搜索课题册
    public function srcJietiCe($jieti_id)
    {
        $data = $this
            ->where('jieti_id', $jieti_id)
            ->with(
                [
                    'fzSchool' => function($query){
                        $query->field('id, jiancheng');
                    },
                    'glJieti' => function($query){
                        $query->field('id, title, shijian, danwei_id')
                            ->with([
                                'glDanwei' => function($query){
                                    $query->field('id, title');
                                },
                            ]);
                    },
                    'ktZcr'=>function($query){
                        $query->field('ketiinfo_id, teacher_id')
                            ->with([
                                'teacher'=>function($q){
                                    $q->field('id, xingming');
                                }
                            ]);
                    },
                    'ktCy'=>function($query){
                        $query->field('ketiinfo_id, teacher_id')
                            ->with([
                                'teacher'=>function($q){
                                    $q->field('id, xingming');
                                }
                            ]);
                    }
                ]
            )
            ->select();

        return $data;
    }


    // 课题主持人关联
    public function ktZcr()
    {
        return $this->hasMany(\app\keti\model\KetiCanyu::class, 'ketiinfo_id', 'id')
            ->where('category_id', 11901);
    }


    // 课题参与人关联
    public function ktCy()
    {
        return $this->hasMany(\app\keti\model\KetiCanyu::class, 'ketiinfo_id', 'id')
            ->where('category_id', 11902);
    }


    // 立项单位关联
    public function fzSchool()
    {
         return $this->belongsTo(\app\system\model\School::class, 'fzdanwei_id','id');
    }


    // 研究类型关联
    public function ktCategory()
    {
         return $this->belongsTo(\app\system\model\Category::class, 'category_id', 'id');
    }


    // 所属学科关联
    public function ktSubject()
    {
         return $this->belongsTo(\app\system\model\Category::class, 'subject_id', 'id');
    }


    // 结题等级关联
    public function ktJdDengji()
    {
         return $this->belongsTo(\app\system\model\Category::class, 'jddengji_id', 'id');
    }


    // 课题册关联
    public function glLixiang()
    {
         return $this->belongsTo(\app\keti\model\Lixiang::class, 'lixiang_id', 'id');
    }


    // 课题册关联
    public function glJieti()
    {
         return $this->belongsTo(\app\keti\model\Jieti::class, 'jieti_id', 'id');
    }


    // 计划结题时间修改器
    public function setJhjtshijianAttr($value)
    {
        return strtotime($value);
    }


    // 计划结题时间获取器
    public function getJhjtshijianAttr($value)
    {
        if ($value>0)
        {
            $value = date('Y-m-d', $value);
        }else{
            $value = "";
        }
        return $value;
    }


    // 结题时间修改器
    public function setJtshijianAttr($value)
    {
        return strtotime($value);
    }


    // 结题时间获取器
    public function getJtshijianAttr($value)
    {
        if ($value>0)
        {
            $value = date('Y-m-d', $value);
        }else{
            $value = "";
        }
        return $value;
    }
}
