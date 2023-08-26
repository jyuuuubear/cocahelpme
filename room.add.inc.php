<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>
<div class="info">
	<div class="ui-thumb">
		<img src="<?=$in['it_img']?>" />
	</div>
</div>
<div class="text">
	<p class="title">
		<?=$in['it_name']?>
		<span><?=number_format($in['it_sell'])?><?=$config['cf_money_pice']?></span>
	</p>
	<div>
		<p><?=$in['it_content']?></p>
	</div>
</div>

<form action="<?=G5_URL?>/room/room_add_update.php" method="post" name="frmItemAdd" enctype="multipart/form-data">
	<input type="hidden" name="type" value="add" />
	<input type="hidden" name="in_id" value="<?=$in['in_id']?>" />
	<input type="hidden" name="mb_id" value="<?=$ch['mb_id']?>" />
	<input type="hidden" name="url" value="<?=$url?>" />
	
	<div class="add-item-form">
		<div class="item-info">
			<input type="file" name="ro_img" class="required" required/>
		</div>
	</div>
	<div class="control-box">
		<button type="submit" class="ui-btn simple">등록하기</button>
	</div>
</form>
