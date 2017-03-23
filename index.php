<?php 
require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'common.func.php';

$act = @$_REQUEST['act'];
$filename = @$_REQUEST['filename'];

$path = 'file';
$info = readDirectory($path);

$redirect = "index.php?path={$path}";
if($act == '创建文件'){
//    echo $path.'--';
//    echo $filename;
    $mes = createFile($path.'/'.$filename);
    alertMes($mes,$redirect);
}elseif($act == 'showContent'){
    //查看文件内容
    $content = file_get_contents($filename);
    //echo $content;
    if(strlen($content)){
        echo "<textarea readonly='readonly' cols='100' rows='10'>{$content}</textarea>";
    }else{
        alertMes("文件没有内容，请编辑后查看",$redirect);
    }
    //高亮显示php代码
    //highlight_string($content);
}elseif($act == 'editContent'){
    //echo "编辑文件";
    $content = file_get_contents($filename);
    $str = <<<EOF
    <form action='index.php?act=doEdit' method='post'>
       <textarea name='content' cols='190' rows='10'>{$content}</textarea></br>
       <input type='hidden' name='filename' value='{$filename}'/>
        <input type="submit" value="修改文件内容"/>
    </form>
EOF;
    echo $str;
}elseif($act == 'doEdit'){
    //修改文件内容的操作
    $content = $_REQUEST['content'];
   // echo $filename;
    if(file_put_contents($filename,$content)){
        $mes = "文件修改成功";
    }else{
        $mes = "文件修改失败";
    }
    alertMes($mes,$redirect);
}elseif($act == 'renameFile'){
    //重命名文件
    $str = <<<EOF
        <form action="index.php?act=doRename" method="post">
            请填写重命名:<input type="text" name="newname" placeholder="重命名"/></br>
            <input type="hidden" name="filename" value="{$filename}"/>
            <input type="submit" value="重命名"/>
        </form>
EOF;
    echo $str;
}elseif($act == 'doRename'){
    //实现重命名的操作
    $newname = @$_REQUEST['newname'];
    $mes = renameFile($filename,$newname);
    alertMes($mes,$redirect);
}elseif($act == 'delFile'){
    //实现删除文件操作
    $mes = delFile($filename);
    alertMes($mes,$redirect);
}elseif($act == 'downFile'){
    //下载文件
    downFile($filename);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert title here</title>
<link rel="stylesheet" href="cikonss.css" />
<script src="jquery-ui/js/jquery-1.10.2.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css"  type="text/css"/>
<style type="text/css">
	body,p,div,ul,ol,table,dl,dd,dt{
		margin:0;
		padding: 0;
	}
	a{
		text-decoration: none;
	}
	ul,li{
		list-style: none;
		float: left;
	}
	#top{
		width:100%;
		height:48px;
		margin:0 auto;
		background: #E2E2E2;
	}
	#navi a{
		display: block;
		width:48px;
		height: 48px;
	}
	#main{
		margin:0 auto;
		border:2px solid #ABCDEF;
	}
	.small{
		width:25px;
		height:25px;
		border:0;
}
</style>
    <script type="text/javascript">
        function show(dis){
            document.getElementById(dis).style.display="block";
        }
        function delFile(filename,path){
            if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
                location.href="index.php?act=delFile&filename="+filename+"&path="+path;
            }
        }
        function delFolder(dirname,path){
            if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
                location.href="index.php?act=delFolder&dirname="+dirname+"&path="+path;
            }
        }
        function showDetail(t,filename){
            $("#showImg").attr("src",filename);
            $("#showDetail").dialog({
                height:"auto",
                width: "auto",
                position: {my: "center", at: "center",  collision:"fit"},
                modal:false,//是否模式对话框
                draggable:true,//是否允许拖拽
                resizable:true,//是否允许拖动
                title:t,//对话框标题
                show:"slide",
                hide:"explode"
            });
        }
        function goBack($back){
            location.href="index.php?path="+$back;
        }
    </script>
</head>

<body>
<div id="showDetail"  style="display:none"><img src="" id="showImg" alt=""/></div>
<h1>在线文件管理器</h1>
<div id="top">
	<ul id="navi">
		<li><a href="index.php" title="主目录"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-home"></span></span></a></li>
		<li><a href="#"  onclick="show('createFile')" title="新建文件" ><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-file"></span></span></a></li>
		<li><a href="#"  onclick="show('createFolder')" title="新建文件夹"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-folder"></span></span></a></li>
		<li><a href="#" onclick="show('uploadFile')"title="上传文件"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-upload"></span></span></a></li>
		<?php 
		$back=($path=="file")?"file":dirname($path);
		?>
		<li><a href="#" title="返回上级目录" onclick="goBack('<?php echo $back;?>')"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-arrowLeft"></span></span></a></li>
	</ul>
</div>

<form action="index.php" method="post" enctype="multipart/form-data">
<table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#abcdef" align="center">
    <tr id="createFolder"  style="display:none;">
        <td>请输入文件夹名称</td>
        <td >
            <input type="text" name="dirname" />
            <input type="hidden" name="path"  value="<?php echo $path;?>"/>
            <input type="submit"  name="act"  value="创建文件夹"/>
        </td>
    </tr>
    <tr id="createFile"  style="display:none;">
        <td>请输入文件名称</td>
        <td >
            <input type="text"  name="filename" />
            <input type="hidden" name="path" value="<?php echo $path;?>"/>
            <input type="submit"  name="act" value="创建文件" />
        </td>
    </tr>
    <tr id="uploadFile" style="display:none;">
        <td >请选择要上传的文件</td>
        <td ><input type="file" name="myFile" />
            <input type="submit" name="act" value="上传文件" />
        </td>
    </tr>
    <tr>
        <td>编号</td>
        <td>名称</td>
        <td>类型</td>
        <td>大小</td>
        <td>可读</td>
        <td>可写</td>
        <td>可执行</td>
        <td>创建时间</td>
        <td>修改时间</td>
        <td>访问时间</td>
        <td>操作</td>
    </tr>
<?php
    if($info['file']){
        $i = 1;
        foreach($info['file'] as $val) {
        $p = $path.'/'.$val;
?>
    <tr>
        <!--编号-->
        <td><?php echo $i;?></td>
        <!--名称-->
        <td><?php echo $val;?></td>
        <!--类型-->
        <td><?php $src=filetype($path.'/'.$val)=='file'?"file_ico.png":"folder_ico.png";?><img src="images/<?php echo $src;?>" title="文件"></td>
        <!--文件大小-->
        <td><?php echo transByte(filesize($p))?></td>
        <!--是否可读-->
        <td><?php $src=is_readable($p)?"correct.png":"error.png"?><img width="30" height="30" src="images/<?php echo $src;?>" ></td>
        <!--是否可写-->
        <td><?php $src=is_writable($p)?"correct.png":"error.png"?><img width="30" height="30" src="images/<?php echo $src;?>" ></td>
        <!--是否可执行-->
        <td><?php $src=is_executable($p)?"correct.png":"error.png"?><img width="30" height="30" src="images/<?php echo $src;?>"></td>
        <!--创建时间-->
        <td><?php echo date('y-m-d H:i:s',filectime($p))?></td>
        <!--修改时间-->
        <td><?php echo date('y-m-d H:i:s',filemtime($p))?></td>
        <!--访问时间-->
        <td><?php echo date('y-m-d H:i:s',fileatime($p))?></td>
        <!--操作-->
        <td>
            <?php
                //先得到文件的扩展名 通过切割判断是不是jpg等图片文件的后缀
                //strtolower：转换为小写 end(explode(".",$val))：取得文件名根据符号"."分割后的最后那一部分，即扩展名
                $strExt = explode(".",$val);
                $ext = strtolower(end($strExt));
                $imageExt = array("gif","jpg","jpeg","png");
                //如果是图片的话查看方式通过jq-ui打开而不是二进制文字
                if(in_array($ext,$imageExt)) {
            ?>
                 <a href="javascript:void(0)" onclick="showDetail('<?php echo $val;?>','<?php echo $p;?>')"><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
            <?php
                }else{
                //用二进制文本打开普通的文件
            ?>
            <a href="index.php?act=showContent&path=<?php echo $path;?>&filename=<?php echo $p;?>" ><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
            <?php }; ?>
        <a href="index.php?act=editContent&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/edit.png"  alt="" title="修改"/></a>|
        <a href="index.php?act=renameFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/rename.png"  alt="" title="重命名"/></a>|
        <a href="index.php?act=copyFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/copy.png"  alt="" title="复制"/></a>|
        <a href="index.php?act=cutFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|
        <a href="#"  onclick="delFile('<?php echo $p;?>','<?php echo $path;?>')"><img class="small" src="images/delete.png"  alt="" title="删除"/></a>|
        <a href="index.php?act=downFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small"  src="images/download.png"  alt="" title="下载"/></a>
        </td>
    </tr>
<?php
     $i++;
        }
    }

?>
</table>
</form>
</body>
</html>