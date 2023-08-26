<?php
include_once('./_common.php');

$ro = sql_fetch("select * from {$g5['room_table']} where ro_id = '{$ro_id}'");

if($ro['ro_id']) {
	if($ro['ro_rotate']=='0') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '1' where ro_id = '{$ro_id}'");
		echo "1";
	}
	if($ro['ro_rotate']=='1') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '2' where ro_id = '{$ro_id}'");
		echo "2";
	}
	if($ro['ro_rotate']=='2') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '3' where ro_id = '{$ro_id}'");
		echo "3";
	}
	if($ro['ro_rotate']=='3') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '4' where ro_id = '{$ro_id}'");
		echo "4";
	}
	if($ro['ro_rotate']=='4') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '5' where ro_id = '{$ro_id}'");
		echo "5";
	}
	if($ro['ro_rotate']=='5') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '6' where ro_id = '{$ro_id}'");
		echo "6";
	}
	if($ro['ro_rotate']=='6') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '7' where ro_id = '{$ro_id}'");
		echo "7";
	}
	if($ro['ro_rotate']=='7') {
		sql_query(" update {$g5['room_table']} set ro_rotate = '0' where ro_id = '{$ro_id}'");
		echo "0";
	}
}
?>
