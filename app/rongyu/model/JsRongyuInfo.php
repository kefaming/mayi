<?php

namespace app\rongyu\model;

// 引用数据模型基类
use app\BaseModel;

class JsRongyuInfo extends BaseModel
{
    // 设置字段信息
    protected $schema = [
        'id' => 'int'
        ,'title' => 'varchar'
        ,'rongyuce_id' => 'int'
        ,'bianhao' => 'varchar'
        ,'hjschool_id' => 'int'
        ,'subject_id' => 'int'
        ,'jiangxiang_id' => 'int'
        ,'pic' => 'varchar'
        ,'hjshijian' => 'int'
        ,'create_time' => 'int'
        ,'update_time' => 'int'
        ,'delete_time' => 'int'
        ,'status' => 'tinyint'
    ];


    //搜索单位获奖荣誉
    public function search($srcfrom)
    {
        $src = [
            'fzschool_id' => array()
            ,'hjschool_id' => array()
            ,'category_id' => array()
            ,'rongyuce_id' => ''
            ,'subject_id' => array()
            ,'searchval' => ''
            ,'page' => 1
            ,'limit' => 10
            ,'field' => 'id'
            ,'order' => 'desc'
            ,'all' => false
        ];
        // 用新值替换初始值
        $src = array_cover($srcfrom, $src);
        $src['fzschool_id'] = str_to_array($src['fzschool_id']);
        $src['hjschool_id'] = str_to_array($src['hjschool_id']);
        $src['category_id'] = str_to_array($src['category_id']);
        $src['subject_id'] = str_to_array($src['subject_id']);

    	$data = $this
    		->when(strlen($src['rongyuce_id']) > 0, function($query) use($src){
                    $query->where('rongyuce_id', $src['rongyuce_id']);
                }, function($query) use($src){
                    $query->when(count($src['fzschool_id']) > 0, function($query) use($src){
                        $query->where('rongyuce_id', 'in', function($q) use($src){
                            $q->name('JsRongyu')
                                ->where('fzschool_id', 'in', $src['fzschool_id'])
                                ->field('id');
                        });
                    });
                })
            ->when(count($src['hjschool_id']) > 0, function($query) use($src){
                $query->where('hjschool_id', 'in', $src['hjschool_id']);
            })
    		->when(count($src['category_id']) > 0, function($query) use($src){
            	$query->where('rongyuce_id', 'in', function($q) use($src){
                    $q->name('JsRongyu')
                        ->where('category_id', 'in', $src['category_id'])
                        ->field('id');
                });
            })
            ->when(count($src['subject_id']) > 0, function($query) use($src){
                $query->where('rongyuce_id', 'in', function($q) use($src){
                    $q->name('JsRongyu')
                        ->where('subject_id', 'in', $src['subject_id'])
                        ->field('id');
                });
            })
            ->when(strlen($src['searchval']) > 0, function($query) use($src){
                $query->where(function($z) use($src){
                    $z->whereOr('title|bianhao', 'like', '%' . $src['searchval'] . '%')
                        ->whereOr('id', 'in', function($q) use($src){
                            $q->name('JsRongyuCanyu')
                                ->distinct(true)
                                ->where('teacher_id', 'in', function($w) use($src){
                                    $w->name('Admin')
                                        ->where('xingming', 'like', '%' . $src['searchval'] . '%')
                                        ->field('id');
                                    })
                                ->field('rongyu_id');
                            });
                    });
                })
            ->with([
                'hjSchool' => function($query){
                    $query->field('id, jiancheng');
                },
                'rySubject' => function($query){
                    $query->field('id, title');
                },
                'jxCategory' => function($query){
                    $query->field('id, title');
                },
                'ryTuce' => function($query){
                    $query->field('id, title, fzschool_id')
                        ->with([
                            'fzSchool'=>function($q){
                                $q->field('id, title');
                            }
                        ]);
                },
                'hjJsry' => function($query){
                    $query->field('rongyu_id, teacher_id')
                        ->with(['teacher' => function($query){
                            $query->field('id, xingming');
                        }]
                    );
                },
                'cyJsry' => function($query){
                    $query->field('rongyu_id, teacher_id')
                        ->with([
                            'teacher' => function($query){
                                $query->field('id, xingming');
                            }
                        ]);
                },
            ])
            ->when($src['all'] == false, function ($query) use($src) {
                $query
                    ->page($src['page'], $src['limit']);
            })
            ->order([$src['field'] => $src['order']])
            ->append(['hjJsName', 'cyJsName'])
    		->select();

    	return $data;
    }


    // 获取需要下载的荣誉信息
    public function srcTuceRy($id)
    {
        // 查询数据
        $data =$this->where('rongyuce_id', $id)
                ->field('id, rongyuce_id, title, bianhao, hjschool_id, subject_id, jiangxiang_id, hjshijian, pic')
                ->with([
                    'hjJsry' => function($query){
                        $query->field('rongyu_id, teacher_id')
                            ->with([
                                'teacher' => function($query){
                                    $query->field('id, xingming');
                                }
                            ]);
                    },
                    'cyJsry' => function($query){
                        $query->field('rongyu_id, teacher_id')
                            ->with([
                                'teacher' => function($query){
                                    $query->field('id, xingming');
                                }]
                            );
                    },
                    'ryTuce' => function($query){
                        $query->field('id, title, fzshijian, fzschool_id')
                            ->with([
                                'fzSchool' => function($q){
                                    $q->field('id, title');
                                }
                            ]);
                    },
                    'rySubject' => function($query){
                        $query->field('id,title');
                    },
                    'hjSchool' => function($query){
                        $query->field('id, jiancheng');
                    },
                    'jxCategory' => function($query){
                        $query->field('id, title');
                    }
                ])
                ->order(['hjschool_id'])
                ->append(['hjJsName', 'cyJsName'])
                ->select();

        return $data;
    }


    //搜索教师荣誉
    public function srcTeacherRongyu($teacher_id)
    {
        // 查询数据
        $data = $this->where('id', 'in', function($query) use($teacher_id){
                $query->name('JsRongyuCanyu')
                    ->where('teacher_id', $teacher_id)
                    ->field('rongyu_id');
            })
            ->where('status', 1)
            ->with(
                [
                    'hjSchool' => function($query){
                        $query->field('id, jiancheng');
                    },
                    'rySubject' => function($query){
                        $query->field('id, title');
                    },
                    'jxCategory' => function($query){
                        $query->field('id, title');
                    },
                    'ryTuce' => function($query){
                        $query->field('id, title, fzschool_id')
                            ->with([
                                'fzSchool' => function($q){
                                    $q->field('id, title, jibie_id')
                                        ->with([
                                            'dwJibie' => function($w){
                                                $w->field('id,title');
                                            }
                                        ]);
                                }
                            ]);
                    },
                    'hjJsry' => function($query){
                        $query->field('rongyu_id, teacher_id')
                            ->with(['teacher' => function($query){
                                $query->field('id, xingming');
                            }]);
                    },
                ]
            )
            // ->append(['hjJsName', 'cyJsName'])
            ->select();

        return $data;
    }


    // 根据获取荣誉时间查询荣誉
    public function searchRySj($srcfrom) {
        $src = [
            'searchval' => ''
            ,'betweentime' => date('Y').'-01-01～' . date('Y-m-d')
            ,'category_ryc' => array()
        ];

        $src = array_cover($srcfrom, $src);
        $src['time'] = explode('～', $src['betweentime']);

        $data = $this
            ->whereBetweenTime('hjshijian', $src['time'][0], $src['time'][1])
            ->when(count($src['category_ryc']) > 0, function ($query) use($src) {
                $query
                    ->where('rongyuce_id', 'in', function ($q) use($src) {
                        $q->name('JsRongyu')
                            ->where('category_id', 'in', $src['category_ryc'])
                            ->where('status', 1)
                            ->field('id');
                    });
            })
            ->field('id, hjschool_id, rongyuce_id, hjshijian')
            ->with([
                'hjSchool' => function($query){
                    $query->field('id, jiancheng');
                },
                'ryTuce' => function($query){
                    $query->field('id, title, fzschool_id')
                        ->with([
                            'fzSchool' => function($q){
                                $q->field('id, title, jibie_id');
                            }
                        ]);
                },
            ])
            ->where('status', 1)
            ->select();
        return $data;
    }


    // 荣誉图册关联
    public function ryTuce()
    {
        return $this->belongsTo(\app\rongyu\model\JsRongyu::class, 'rongyuce_id', 'id');
    }


    // 获奖单位关联
    public function hjSchool()
    {
         return $this->belongsTo(\app\system\model\School::class, 'hjschool_id', 'id');
    }

    // 颁奖单位关联
    public function fzSchool()
    {
         return $this->belongsTo(\app\system\model\School::class, 'fzschool_id', 'id');
    }

    // 荣誉所属学科
    public function rySubject()
    {
         return $this->belongsTo(\app\teach\model\Subject::class, 'subject_id', 'id');
    }

    // 奖项关联
    public function jxCategory()
    {
         return $this->belongsTo(\app\system\model\Category::class, 'jiangxiang_id', 'id');
    }

    // 获奖人信息关联
    public function hjJsry()
    {
        return $this->hasMany(\app\rongyu\model\JsRongyuCanyu::class, 'rongyu_id', 'id')->where('category_id',11901);
    }


    // 获奖人与参与教师关联
    public function allJsry()
    {
        return $this->hasMany(\app\rongyu\model\JsRongyuCanyu::class, 'rongyu_id', 'id');
    }


    // 参与人关联
    public function cyJsry()
    {
        return $this->hasMany(\app\rongyu\model\JsRongyuCanyu::class, 'rongyu_id', 'id')->where('category_id', 11902);
    }


    // 荣誉级别
    public function getJibieAttr()
    {
        $jibie = '';

        if($this->fzschool){
            $jibie = $this->fzSchool->jibie;
        }

         return $jibie;
    }


    // 发证时间修改器
    public function setHjshijianAttr($value)
    {
        return strtotime($value);
    }

    // 发证时间获取器
    public function getHjshijianAttr($value)
    {
        if ($value>0)
        {
            $value = date('Y-m-d',$value);
        }else{
            $value = "";
        }
        return $value;
    }
}
