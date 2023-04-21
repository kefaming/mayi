<?php
// 事件定义文件
return [
    'bind'      => [
        'myevent'=>'app\event\MyEvent',
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'kslu' => [
            '\app\listener\KaoshiLuru'
        ],
        'ksedit' => [
            '\app\listener\KaoshiEdit'
        ],
        'tjlog' => [
            '\app\listener\TongjiLog'
        ],
        'kstj' => [
            '\app\listener\KaoshiTongJi'
        ],
        'mybanji' => [
            '\app\listener\MyBanjiIds'
        ],
        'lrfg' => [
            '\app\listener\LuruFengong'
        ],
    ],
    'subscribe' => [
    ],
];
