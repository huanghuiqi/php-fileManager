<!--存放所有跟目录相关的操作-->
<?php

//写一个读目录的函数 有文件 有目录
function readDirectory($path){
    //打开目录
    $handle = opendir($path);
    while(($item = readdir($handle))!==false){
        //.和..这两个特殊目录得先排除 因为做不了任何操作
        if($item!='.' && $item!='..'){
            if(is_file($path.'/'.$item)){
                $arr['file'][] = $item;
            }
            if(is_dir($path.'/'.$item)){
                $arr['dir'][] = $item;
            }
        }
    };
    closedir($handle);
    return $arr;
}
$path='file';
print_r(readDirectory($path));
?>