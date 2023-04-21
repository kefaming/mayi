<?php

// 分数验证
function manfenvalidate($defen, $manfen)
{
	$data['val'] = 1;
	$data['msg'] = '验证通过';

	if ($manfen == "") {
		$data['val'] = 0;
		$data['msg'] = '本学科不参加考试';
		return $data;
	}

	if (is_numeric($defen) == false) {
		$data['val'] = 0;
		$data['msg'] = '得分必须是数字';
		return $data;
	}

	if ( $defen < 0 ) {
		$data['val'] = 0;
		$data['msg'] = '得分必须大于等于0';
		return $data;
	}

	if ($defen > $manfen) {
		$data['val'] = 0;
		$data['msg'] = '得分必须必须小于等于'.$manfen;
		return $data;
	}
	return $data;
}