<?php
//转换文件字节大小
    function transByte($size){
        //byte kb mb gb tb eb
        $arr = array('B','KB','MB','GB','TB');
        $i = 0;
        while($size > 1024){
            $size/= 1024;
            $i++;
        }
        return round($size).$arr[$i];  //number
    }

//创建文件
//    basename返回路径中的文件名部分
//    touch 如果文件不存在 将会被创建
function createFile($filename){
//        第一步：验证文件名的合法性是否包含/,*,<>,?,|
    $pattern = "/[\/,\*,<>,\?,\|]/";
    if(!preg_match($pattern,basename($filename))){
        //第二步：验证是否包含同名文件
        if(!file_exists($filename)){
//            第三步：通过touch函数创建
            if(touch($filename)){
                return "创建成功";
            }else{
                return "创建失败";
            }
        }else{
            return "文件已存在，请重命名后创建";
        }
    }else{
        return "非法文件名";
    }
}

//重命名文件
function renameFile($oldname,$newname){
    //echo $oldname.'--'.$newname;
    //第一步 验证是否是合法文件名
    if (checkFileName($newname)){
        //第二步 是否存在同名文件
        $path = dirname($oldname);
        if(!file_exists($path.'/'.$newname)){
            if(rename($oldname,$path.'/'.$newname)){
                return "重命名成功";
            }else{
                return "重命名失败";
            }
        }else{
            return "存在同名文件，请重新命名";
        }
    }else{
        return "非法文件名";
    }
}

//检测重命名文件的名字是否合法
function checkFileName($filename){
    $pattern = "/[\/,\*,<>,\?,\|]/";
    if(preg_match($pattern,$filename)){
        return false;
    }else{
        return true;
    }
}

//删除文件
function delFile($filename){
    if(unlink($filename)){
        return "文件删除成功";
    }else{
        return "文件删除失败";
    }
}

//下载文件
function downFile($filename){
    //要下载的文件名 可以写死
    header('Content-Disposition:attachment;filename='.basename($filename));
    //文件大小
    header('Content-Length:'.filesize($filename));
    ob_clean();//清空（擦掉）输出缓冲区
    flush();//刷新输出缓冲
    //文件路径
    readfile($filename);
}
?>


