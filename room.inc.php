<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
include_once('./_config.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/room/css/style.css">', 0);

// 캐릭터 마이룸 설정 가져오기

if(!$ch['ch_room_bak']) {
	$ch = sql_fetch("select mb_id, ch_name, ch_room_bak from {$g5['character_table']} where mb_id =  '{$mb_id}'");
}

$is_mine = $character['mb_id'] == $mb_id ? true : false;

// 이미지 리스트 가져오기
$sql = sql_query("select * from {$g5['room_table']} where mb_id = '{$mb_id}' order by ro_order");
$room_config = array();

$room = array();
for($i=0; $row = sql_fetch_array($sql); $i++) { $room[] = $row; }
?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="room-movie-link">
	<select onchange="location.href='?mb_id='+this.value;" style="border-radius:9em; border-color:#000; height:25px;">
		<option value="<?=$character['mb_id']?>">친구방 이동</option>
		<?
			$relation_list = "select * from {$g5['member_table']} order by mb_no asc";
			$relation = sql_query($relation_list);
			for($i=0; $row = sql_fetch_array($relation); $i++) { 
		?>
			<option value="<?=$row['mb_id']?>" <? if($mb_id == $row['mb_id']) { ?>selected<? } ?>><?=get_character_name($row['ch_id'])?></option>
		<? } ?>
	</select>
</div>

<div class="room-pannel">
	<div class="roomWrap">
		<? if($is_mine) { ?>
			<div class="objList trans theme-box">
				<button type="button" class="close theme-box" onclick="$('.objList').toggleClass('open');"></button>
				<div class="inner">
					<ul id="obj_list">
						<? 
							for($i=0; $i < count($room); $i++) {
								$ro = $room[$i];
						?>

							<li>
								<div class="item" data-idx="<?=$ro['ro_id']?>" id='<?=$ro['ro_id']?>'>
									<em><img src="<?=$ro['ro_img']?>" style="<?=($ro['ro_eye']=='1') ? 'filter:grayscale(1);' : ''?>"/></em>
									<div class="control">
										<button type="button" onclick="fn_room_dir(this, 'prev');" data-idx="<?=$ro['ro_id']?>">
											▲
										</button>
										<button type="button" onclick="fn_room_dir(this, 'next');" data-idx="<?=$ro['ro_id']?>">
											▼
										</button>
										<button type="button" onclick="fn_room_eye(this);" data-idx="<?=$ro['ro_id']?>" class="eye">
										<?=($ro['ro_eye']=='1') ? 'X' : 'O'?>
										</button>
										<button type="button" onclick="fn_room_rotate(this);" data-idx="<?=$ro['ro_id']?>" class="rotate">
										<i class="fas fa-sync-alt"></i>
										</button>
										<button type="button" onclick="fn_room_flip(this);" data-idx="<?=$ro['ro_id']?>" class="flip">
										<i class="fas fa-exchange-alt"></i>
										</button>
										<button type="button" onclick="fn_room_del(this);" class="del" data-idx="<?=$ro['ro_id']?>">
											삭제
										</button>
									</div>
								</div>
							</li>
						<? } ?>
					</ul>
				</div>
			</div>
		<? } ?>

		<div class="objCanvas">
			<div id="room_area" class="none-trans" style="width:<?=$room_w?>px; height:<?=$room_h?>px;<?if($ch['ch_room_bak']){?> background-image:url(<?=$ch['ch_room_bak']?>);<?}?>">
				<? 
					for($i=0; $i < count($room); $i++) {
						$ro = $room[$i];
				?>
								
				<div data-idx="<?=$ro['ro_id']?>" style="top:<?=$ro['ro_top']?>px; left:<?=$ro['ro_left']?>px; z-index:<?=$ro['ro_order']?>; display: <?=($ro['ro_eye']=='1') ? 'none' : 'block'?>;<?=($ro['ro_flip']=='1') ? 'transform:scaleX(-1);' : ''?> 
				<?$degrees = intval($ro['ro_rotate']) * 45; echo "transform:rotate(${degrees}deg);";?>transform-origin: top left;" class="obj draggable" id="<?=$ro['ro_id']?>-room">
						<img src="<?=$ro['ro_img']?>" />
					</div>
				<? } ?>
			</div>
		</div>
	</div>
</div>

<? if($is_mine) { ?>
	<script>
	$('#obj_list .item').on('mouseenter', function() {
		var idx = $(this).attr('data-idx');
		$('#room_area').addClass('over');
		$('#room_area .obj').removeClass('over');
		$('#room_area .obj[data-idx="'+idx+'"]').addClass('over');
	}).on('mouseleave', function() {
		$('#room_area').removeClass('over');
		$('#room_area .obj').removeClass('over');
	});

	$(".draggable" ).each(function() {
		$obj = $(this);
		$obj.draggable({
			stop:function(event, ui) {
				var idx = $(event.target).attr('data-idx');
				var top = ui.position.top;
				var left = ui.position.left;

				if(top < 0) {
					top = 0;
				}
				if(left < 0) {
					left = 0;
				}
				if(top > (<?=$room_h?>-10)) {
					top = (<?=$room_h?>-10);
				}
				if(left > (<?=$room_w?>-10)) {
					left = (<?=$room_w?>-10);
				}

				$(event.target).css('top', top +  "px");
				$(event.target).css('left', left +  "px");

				var sendData = {ro_id:idx, ro_top:top, ro_left:left};
				var url = g5_url + "/room/room_obj_update.php";

				$.ajax({
					type: 'post'
					, url : url
					, data: sendData
					, success : function(data) {console.log('success')}
				});
			}
		});
	});

	function fn_room_dir(obj, dir) {
		var idx = $(obj).attr('data-idx');

		var url = g5_url + "/room/room_obj_dir.php";
		var sendData = {ro_id:idx, dir:dir, mb_id:<?=$member['mb_id']?>};
		$.ajax({
			type: 'post'
			, url : url
			, data: sendData
			, dataType:"json"
			, success : function(data) {
				console.log('success');
				if(data.my) {
					var li = $('#obj_list .item[data-idx="'+idx+'"]').closest('li');
					if(dir == 'next') {
						li.next().after(li);
					} else {
						li.prev().before(li);
					}
					$('#room_area .obj[data-idx="'+data.other+'"]').css('z-index', data.other_order);
					$('#room_area .obj[data-idx="'+data.my+'"]').css('z-index', data.my_order);
				}
			}
		});
	}

	function fn_room_del(obj) {
		// 오브젝트 삭제
		var idx = $(obj).attr('data-idx');
		var url = g5_url + "/room/room_obj_delete.php";
		var sendData = {ro_id:idx};
		$.ajax({
			type: 'post'
			, url : url
			, data: sendData
			, success : function(data) {
				console.log(data);
				if(data == 'Y') {
					// 삭제에 성공했을 시, 목록에서도 제거해준다.
					$('#obj_list .item[data-idx="'+idx+'"]').remove();
					$('#room_area .obj[data-idx="'+idx+'"]').remove();
				}
			}
		});
	}

	function fn_room_eye(obj) {
		// 오브젝트 눈
		var idx = $(obj).attr('data-idx');
		var url = g5_url + "/room/room_obj_eye.php";
		var sendData = {ro_id:idx};
		$.ajax({
			type: 'post'
			, url : url
			, data: sendData
			, success : function(data) {
				console.log(data);
				if(data == 'see') {
					// 삭제에 성공했을 시, 목록에서도 제거해준다.
					$('.eye[data-idx="'+idx+'"]').text("X");
					$('.item[data-idx="'+idx+'"]').css("filter","grayscale(1)");
					$('.obj[data-idx="'+idx+'"]').css("display","none");
				}else {
					$('.eye[data-idx="'+idx+'"]').text("O");
					$('.item[data-idx="'+idx+'"]').css("filter","grayscale(0)");
					$('.obj[data-idx="'+idx+'"]').css("display","block");
				}
			}
		});
	}

	function fn_room_flip(obj) {
		// 오브젝트 반전
		var idx = $(obj).attr('data-idx');
		var url = g5_url + "/room/room_obj_flip.php";
		var sendData = {ro_id:idx};
		$.ajax({
			type: 'post'
			, url : url
			, data: sendData
			, success : function(data) {
				console.log(data);
				if(data == 'flip') {
					$('.obj[data-idx="'+idx+'"]').css("transform","scaleX(-1)");
				}else {
					$('.obj[data-idx="'+idx+'"]').css("transform","scaleX(0)");
				}
			}
		});
	}

	function fn_room_rotate(obj) {
		// 오브젝트 회전
		var idx = $(obj).attr('data-idx');
		var url = g5_url + "/room/room_obj_rotate.php";
		var sendData = {ro_id:idx};
		$.ajax({
			type: 'post'
			, url : url
			, data: sendData
			, success : function(data) {
				console.log(data);
				if(data == '0') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(0)");
				}else if(data == '1') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(45deg)");
				}else if(data == '2') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(90deg)");
				}else if(data == '3') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(135deg)");
				}else if(data == '4') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(180deg)");
				}else if(data == '5') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(225deg)");
				}else if(data == '6') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(270deg)");
				}else if(data == '7') {
					$('.obj[data-idx="'+idx+'"]').css("transform","rotate(315deg)");
				}
			}
		});
	}

	</script>
<? } ?>


