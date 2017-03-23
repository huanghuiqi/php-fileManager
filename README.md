# php-fileManager
web在线文件管理系统

###文件操作    

```file_get_contents($filename)```  查看文件内容    

```file_put_contents($filename,$content)```  修改文件内容     

```@$_REQUEST[参数]```  获取通过get传递过来的值          

```require_once xxx.php```  引入某个php文件，里面可能是封装了函数       

```preg_match(pattern , str)```  一参是正则表达式，二参是匹配的字符串，返回布尔值    

```file_exists()```  判断文件是否存在，返回布尔值    

```touch()```   打开一个文件        

```$handle = opendir($path) ```  打开一个目录  

```readdir($handle)```  读取一个目录       

```closedir($handle)```  读取完毕后要关闭目录    

```explode(切割符,要切割的字符串)``` 返回一个数组    

```end(Array)``` 返回数组中的最后一个元素的值

```rename($oldname,$newname)```  一般用来做重命名    

```unlink($filename)```   删除文件   
```
//浏览器下载文件
  header('Content-Disposition:attachment;filename='.basename($filename));
    //文件大小
    header('Content-Length:'.filesize($filename));
    ob_clean();//清空（擦掉）输出缓冲区
    flush();//刷新输出缓冲
    //文件路径
    readfile($filename);
```
###文件夹操作
