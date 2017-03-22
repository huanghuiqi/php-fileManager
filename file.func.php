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

?>


