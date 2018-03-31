<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN"><head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="Content-Language" content="zh-CN">
    <title><?php $user = $this->session->userdata('user');
        if(isset($user)){
            echo $user->username."的博客 - SYSIT个人博客";
        }?></title>
    <base href="<?php echo site_url();?>">
    <link rel="stylesheet" href="assets/css/space2011.css" type="text/css" media="screen">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.css" media="screen">
    <script type="text/javascript" src="assets/js/jquery-1.11.2.js"></script>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/jquery_002.js"></script>
    <script type="text/javascript" src="assets/js/oschina.js"></script>
    <style type="text/css">
        body,table,input,textarea,select {font-family:Verdana,sans-serif,宋体;}
    </style>
</head>
<body>
<!--[if IE 8]>
<style>ul.tabnav {padding: 3px 10px 3px 10px;}</style>
<![endif]-->
<!--[if IE 9]>
<style>ul.tabnav {padding: 3px 10px 4px 10px;}</style>
<![endif]-->
<div id="OSC_Screen"><!-- #BeginLibraryItem "/Library/OSC_Banner.lbi" -->
    <div id="OSC_Banner">
        <div id="OSC_Slogon"><?php
            if(isset($user)){
                echo $user->username."'s Blog";
            }?></div>
        <div id="OSC_Channels">
            <ul>
                <li><a href="#" class="project"><?php if(isset($user)){echo $user->mood;}?></a></li>

            </ul>
        </div>
        <div class="clear"></div>
    </div><!-- #EndLibraryItem --><div id="OSC_Topbar">
        <div id="VisitorInfo">
            当前访客身份：
            <?php
            if(isset($user)){
                echo $user->username;
                ?>
                <a href='user/logout'>退出</a>
            <?php }else {
                redirect("Welcome/index");
            } ?>
            <span id="OSC_Notification">
			<a href="#" class="msgbox" title="进入我的留言箱">你有<em>0</em>新留言</a>
																				</span>
        </div>
        <div id="SearchBar">
            <form action="#">
                <input name="user" value="154693" type="hidden">
                <input id="txt_q" name="q" class="SERACH" value="在此空间的博客中搜索" onblur="(this.value=='')?this.value='在此空间的博客中搜索':this.value" onfocus="if(this.value=='在此空间的博客中搜索'){this.value='';};this.select();" type="text">
                <input class="SUBMIT" value="搜索" type="submit">
            </form>
        </div>
        <div class="clear"></div>
    </div>
	<div id="OSC_Content">
<div id="AdminScreen">
    <div id="AdminPath">
        <a href="Welcome/index_login">返回我的首页</a>&nbsp;»
    	<span id="AdminTitle">博客文章管理</span>
    </div>
    <div id="AdminMenu"><ul>
	<li class="caption">个人信息管理		
		<ol>
			<li><a href="inbox.htm">站内留言(0/1)</a></li>
			<li><a href="profile.htm">编辑个人资料</a></li>
			<li><a href="chpwd.htm">修改登录密码</a></li>
			<li><a href="userSettings.htm">网页个性设置</a></li>
		</ol>
	</li>		
</ul>
<ul>
	<li class="caption">博客管理	
		<ol>
			<li><a href="Welcome/new_blog">发表博客</a></li>
			<li><a href="Welcome/blog_catalogs">博客设置/分类管理</a></li>
			<li class="current"><a href="Welcome/blogs">文章管理</a></li>
			<li><a href="Welcome/blog_omments">博客评论管理</a></li>
		</ol>
	</li>
</ul>
</div>
    <div id="AdminContent">
<div class="MainForm BlogArticleManage">
  <h3 class="title">共有 <?php echo $total?> 篇博客，每页显示 <?php echo $count?>个，共 <?php echo ceil($total/$count)?>页</h3>
    <div id="BlogOpts">
	<a href="javascript:;" onclick="select_all();">全选</a>&nbsp;|
	<a href="javascript:;" onclick="unselect_all();">取消</a>&nbsp;|
	<a href="javascript:;" onclick="select_other();">反向选择</a>&nbsp;|
	<a href="javascript:;" id="del_checked">删除选中</a>
  </div>
  <ul>
      <?php foreach ($article as $articles){?>
		<li class="row_1">
		<input name="blog" value="<?php echo $articles->article_id?>" type="checkbox">
		<a href="Welcome/content" target="_blank"><?php echo $articles->title?></a>
		<small><?php echo $articles->post_date?></small>
	</li>
      <?php }?>

	  </ul>
    <?php echo $links?>
    </div>
<script type="text/javascript">
    $(function(){
        $('#del_checked').on('click',function(){
            var box = $(":checked");
            if(box.length>0) {
                if (confirm("确认要删除选中的文章吗？")) {

                    var ids = [];
                    for (var i = 0; i < box.length; i++) {
                        ids.push($(box[i]).val());
                    }
                    $.get('Welcome/del_article', {
                        ids: ids
                    }, function (data) {
                        if (data == 'success') {
                            location.href = 'Welcome/blogs';
                        }
                    }, 'text');
                }
            }else{
                alert("请选择要删除的内容");

            }


    });});


function select_all(){
	$("input[name='blog']").attr("checked", true);
}
function unselect_all(){
	$("input[name='blog']").attr("checked", false); 
}
function select_other(){
	jQuery.each($("input[name='blog']"), function(i, n){
		n.checked = !n.checked;
	}); 
}
//function delete_sel(){
//	var blogids = "";
//	jQuery.each($("input[name='blog']"), function(i, n){
//		if(n.checked){
//			blogids += $(this).val()+",";
//		}
//	});
//	if(blogids.length > 0) {
////        if (!confirm("确认要删除选中的文章吗？")) {
////            $.get("Welcome/del_article", {
////                id : blogids
////            }, function (data) {
////                if (data == 'error') {
////                    $('#error_msg').html("<span class='ok_msg'>请删除自己的文章</span>")
////                    $('#error_msg').show("fast");
////
////                }
////                else if (data == 'success') {
////                    location.href = 'Welcome/blogs';
////                }
////            });
////        }
//    }
//	else
//		alert("请选择要删除的文章");
//}
//-->
</script></div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#AdminTitle').text('博客文章管理');
});
$('.AutoCommitForm').ajaxForm({
    success: function(html) {	
		$('#error_msg').hide();
		if(html.length>0)
			$('#error_msg').html("<span class='error_msg'>"+html+"</span>");
		else
			$('#error_msg').html("<span class='ok_msg'>操作已成功完成</span>")
		$('#error_msg').show("fast");
    }
});
$('.AutoCommitJSONForm').ajaxForm({
	dataType: 'json',
    success: function(json) {	
		$('#error_msg').hide();
		if(json.error==0){
			if(json.msg)
				$('#error_msg').html("<span class='ok_msg'>"+json.msg+"</span>");
			else
				$('#error_msg').html("<span class='ok_msg'>操作已成功完成</span>");
		}
		else {
			if(json.msg)
				$('#error_msg').html("<span class='error_msg'>"+json.msg+"</span>");
			else
				$('#error_msg').html("<span class='error_msg'>操作已成功完成</span>");
		}
		$('#error_msg').show("fast");
    }
});
//-->
</script>
</div>
	<div class="clear"></div>
	<div id="OSC_Footer">© 赛斯特(WWW.SYSIT.ORG)</div>
</div>
<script type="text/javascript" src="js/space.htm" defer="defer"></script>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$('a.fancybox').fancybox({titleShow:false});
});

function pay_attention(pid,concern_it){
	if(concern_it){
		$("#p_attention_count").load("/action/favorite/add?mailnotify=true&type=3&id="+pid);
		$('#attention_it').html('<a href="javascript:pay_attention('+pid+',false)" style="color:#A00;">取消关注</a>');	
	}
	else{
		$("#p_attention_count").load("/action/favorite/cancel?type=3&id="+pid);
		$('#attention_it').html('<a href="javascript:pay_attention('+pid+',true)" style="color:#3E62A6;">关注此文章</a>');
	}
}
//-->
</script>
</body></html>