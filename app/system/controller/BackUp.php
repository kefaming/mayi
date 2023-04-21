<?php

namespace app\system\controller;

// 引用控制器基类
use app\base\controller\AdminBase;
// 引用数据库备份类文件
use wamkj\thinkphp\Backup as up;

class BackUp extends AdminBase
{
    protected $upcnf;


    protected function initialize()
    {
        $this->upcnf = config('shangma.backup');
    }


    public function index()
    {
        // 设置要给模板赋值的信息
        $list['webtitle'] = '文件列表';
        $list['dataurl'] = '/system/backup/data';

        // 模板赋值
        $this->view->assign('list', $list);
        // 渲染模板
        return $this->view->fetch();
    }


    //  获取单位列表数据
    public function ajaxData()
    {
        // 获取参数
        $src = $this->request
            ->only([
                'page' => '1'
                ,'limit' => '10'
                ,'field' => 'time'
                ,'order' => 'desc'
            ],'POST');
        $src['order'] == 'desc' ? $src['order'] = SORT_DESC
                : $src['order'] = SORT_ASC;   # 数据排序
        $start = $src['page'] * $src['limit'] - $src['limit'];

        $db= new up($this->upcnf);
        $data = $db->fileList();

        $cnt = count($data);
        $data = \app\facade\Tools::sortArrByManyField($data, $src['field'], $src['order']);
        $data = array_slice($data, $start, $src['limit']);

        foreach ($data as $key => $value) {
           $data[$key]['ltime'] = $value['time'];
           $data[$key]['time'] = date('Y-m-d H:i:s', $value['time']);
           $data[$key]['size'] = format_bytes($value['size']);
           $data[$key]['part'] = $value['part'];
           $data[$key]['compress'] = $value['compress'];
           $data[$key]['status'] = '正常';
        }
        $data = reset_data($data, $cnt);

        return json($data);
    }


    // 数据库备份
    public function export()
    {
        $db= new up($this->upcnf);
        if(request()->isPost()){
            $tables = $db->dataList();
            foreach ($tables as $key => $value) {
                $input['tables'][] = $value['name'];
            }

           $fileinfo  =$db->getFile();
           //检查是否有正在执行的任务
           $lock = "{$fileinfo['filepath']}backup.lock";
           if(is_file($lock)){
               $this->error('检测到有一个备份任务正在执行，请稍后再试！');
           } else {
               //创建锁文件
               file_put_contents($lock,time());
           }
           // 检查备份目录是否可写
           is_writeable($fileinfo['filepath']) || $this->error('备份目录不存在或不可写，请检查后重试！');

           //缓存锁文件
           session('lock', $lock);
           //缓存备份文件信息
           session('backup_file', $fileinfo['file']);
           //缓存要备份的表
           session('backup_tables', $input['tables']);
           //创建备份文件
           if(false !== $db->Backup_Init()){
                $data = [
                    'msg' => '初始化成功！'
                    ,'code' => 1
                    ,'data' => [
                        'tab' => [
                            'id' => 0
                            ,'start' => 0
                        ]
                    ]
                ];
           }else{
               $data = [
                    'msg' => '初始化失败，备份文件创建失败！'
                    ,'code' => 0
                    ,'data' => [
                        'tab' => [
                            'id' => 0
                            ,'start' => 0
                        ]
                    ]
                ];
           }
        }else if(request()->isGet()){
           $tables =  session('backup_tables');
           $file=session('backup_file');

           $id=input('id');
           $start=input('start');
           $start= $db->setFile($file)->backup($tables[$id], $start);

           if(false === $start){
                $data = [
                    'msg' => '备份出错！'
                    ,'code' => 0
                    ,'data' => [
                        'tab' => [
                            'id' => 0
                            ,'start' => 0
                        ]
                    ]
                ];
           }else if(0 === $start){
               if(isset($tables[++$id])){
                   $data = [
                        'msg' => '备份完成！'
                        ,'code' => 1
                        ,'data' => [
                            'tab' => [
                                'id' => $id
                                ,'start' => 0
                            ]
                        ]
                    ];
               } else { //备份完成，清空缓存
                   unlink(session('lock'));
                   session('backup_tables',null);
                   session('backup_file',null);
                   $data = [
                        'msg' => '备份完成！'
                        ,'code' => 0
                        ,'data' => [
                            'tab' => [
                                'id' => 0
                                ,'start' => 0
                            ]
                        ]
                    ];
               }
           }else if(is_array($start)) {
                $data = [
                    'msg' => '备份完成！'
                    ,'code' => 1
                    ,'data' => [
                        'tab' => [
                            'id' => $id
                            ,'start' => $start[0]
                        ]
                    ]
                ];
            }
        }else{
            $data = [
                'msg' => '参数错误！'
                ,'code' => 0
                ,'data' => [
                    'tab' => [
                        'id' => 0
                        ,'start' => 0
                    ]
                ]
            ];
        }

        return json($data);
    }


    // 数据库恢复
    public function import()
    {
        // 获取参数
        $src = $this->request
                ->only([
                    'time' => 0
                    ,'part' => null
                    ,'start' => null
                ], 'POST');
        $time = $src['time'];
        $part = $src['part'];
        $start = $src['start'];

        $db= new up($this->upcnf);

        if(is_numeric($time) && is_null($part) && is_null($start)){
            $list = $db->getFile('timeverif', $time);
            if(is_array($list)){
               session('backup_list', $list);
               $a = session('backup_list');
               $this->success('初始化完成！', '', array('part' => 1, 'start' => 0));
            }else{
                $this->error('备份文件可能已经损坏，请检查！');
            }
        }else if(is_numeric($part) && is_numeric($start)){
                $list=session('backup_list');
                $start= $db->setFile($list)->import($start);

                if( false===$start){
                    $this->error('还原数据出错！');
                }elseif(0 === $start){
                    if(isset($list[++$part])){
                       $data = array('part' => $part, 'start' => 0);
                       $this->success("正在还原...#{$part}", '', $data);
                    } else {
                       session('backup_list', null);
                       $this->success('还原完成！');
                    }
                }else{
                    $data = array('part' => $part, 'start' => $start[0]);
                    if($start[1]){
                       $rate = floor(100 * ($start[0] / $start[1]));
                       $this->success("正在还原...#{$part} ({$rate}%)", '', $data);
                    } else {
                       $data['gz'] = 1;
                       $this->success("正在还原...#{$part}", '', $data);
                    }
                    $this->success("正在还原...#{$part}", '');
                }
        }else{
            $this->error('参数错误！');
        }
    }


    // 删除备份
    public function delete($time)
    {
        $db= new up($this->upcnf);
        $data = $db->delFile($time);

        // 根据更新结果设置返回提示信息
        $data ? $data = ['msg' => '删除成功', 'val' => 1]
            : $data = ['msg' => '数据处理错误', 'val' => 0];

        // 返回信息
        return json($data);
    }


    /**
    * 下载备份文件
    */
   public function down($time = 0){
        $db= new up($this->upcnf);
        $list = $db->getFile('timeverif', $time);
        $url = $list['1']['1'];
        $l = strripos($url, '\\', 0) + 1;
        $title = substr($url, $l, strlen($url) - $l);
        return download($url, $title);
   }


}
