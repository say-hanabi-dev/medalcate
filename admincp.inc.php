<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$medalcates = C::t('#medalcate#medalcate')->fetch_all();

cpheader();
if($_GET['act']=='del'){
	if(intval($_GET['cateid']) > 0){
		C::t('#medalcate#medalcate')->delete_by_cateid(intval($_GET['cateid']));
		cpmsg('删除成功', 'action=plugins&operation=config&do='.$pluginid, 'succeed');
	}else{
		cpmsg('参数错误', '', 'error');
	}
}
elseif($_GET['act']=='add'){
	if(submitcheck('medaladd') && !empty($_POST['catename'])){
		C::t('#medalcate#medalcate')->insert_by_catename($_POST['catename']);
		cpmsg('添加成功', 'action=plugins&operation=config&do='.$pluginid, 'succeed');
	}else{
		cpmsg('参数错误', 'action=plugins&operation=config&do='.$pluginid, 'error');
	}
}
elseif($_GET['act']=='edit'){
	if(submitcheck('medaledit') && intval($_GET['cateid'])>0){
		C::t('#medalcate#medalcate')->update_by_cateid(intval($_GET['cateid']), $_POST['catename'], $_POST['medalid'], $_POST['displayorder']);
		cpmsg('编辑成功', 'action=plugins&operation=config&do='.$pluginid, 'succeed');
	}else{
		cpmsg('参数错误', 'action=plugins&operation=config&do='.$pluginid, 'error');
	}
}
elseif ($_GET['act']=='detail') {
	if(intval($_GET['cateid']) > 0){
		$medalcate = C::t('#medalcate#medalcate')->fetch_by_cateid(intval($_GET['cateid']));
		$medals = C::t('forum_medal')->fetch_all_data(1);
		showformheader('plugins&operation=config&do='.$pluginid.'&identifier=medalcate&act=edit&cateid='.$_GET['cateid']);
?>
		分类 I D:<input type="text" name='cateid' value='<?=$medalcate[0]['cateid']?>' disabled></input><br />
		分类名称:<input type="text" name='catename' value='<?=$medalcate[0]['catename']?>'></input><br />
		显示顺序:<input type="text" name='displayorder' value='<?=$medalcate[0]['displayorder']?>'></input><br />
		勋章列表:<select id="medalselect" onchange="changeSelect()" multiple size=20>
				<?php
					foreach($medals as $medal){?>
						<option value="<?=$medal['medalid']?>"><?=$medal['name']?></option>
					<?php
					}
				?>
				</select>
		(按住Ctrl以选择多个)
		<input type="hidden" name="medalid" id="medalid" value="" />
		<script>
			medalInCate=[<?=implode(",",explode("|",$medalcate[0]['medalid']))?>];
			console.log(medalInCate)
			var selectbox=document.getElementById("medalselect");
			for(var i=0;i<selectbox.options.length;i++){
				console.log(selectbox.options[i].value);
				if(medalInCate.includes(parseInt(selectbox.options[i].value))){
					selectbox.options[i].selected=true;
				}
			}
			changeSelect();
			function changeSelect(){
				var medals=[];
				var selectbox=document.getElementById("medalselect");
				for(var i=0;i<selectbox.options.length;i++){
					if(selectbox.options[i].selected){
						medals.push(selectbox.options[i].value);
					}
				}
				medalid=document.getElementById("medalid");
				medalid.value=medals;
				medalid.value=medalid.value.replace(RegExp(",","g"),"|");
			}
		</script>
<?php
		showsubmit('medaledit');
		showformfooter();
	}else{
		cpmsg('参数错误', '', 'error');
	}
}
else{
	showtableheader();
	showsubtitle(array('ID','分类名','勋章列表','显示顺序','',''));
	foreach($medalcates as $cate){
		showtablerow('',array() ,array($cate['cateid'], $cate['catename'], $cate['medalid'], $cate['displayorder'], '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=medalcate&act=detail&cateid='.$cate['cateid'].'">编辑</a>', '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=medalcate&act=del&cateid='.$cate['cateid'].'">删除</a>'));
	}
	showtablefooter();
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=medalcate&act=add');
?>
	添加分类:<input type="text" name='catename'></input>
<?php
		showsubmit('medaladd');
		showformfooter();
}

?>