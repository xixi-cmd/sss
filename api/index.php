<?php
  function parseUrlParam($query){
  $queryArr = explode('&', $query);
  $params = array();
  if($queryArr[0] !== ''){
      foreach( $queryArr as $param ){
          list($name, $value) = explode('=', $param);
          $params[urldecode($name)] = urldecode($value);
      }
  }
  return $params;
  }
  //设置URL参数数组
  function setUrlParams($cparams, $url = ''){
  $parse_url = $url === '' ? parse_url($_SERVER["REQUEST_URI"]) : parse_url($url);
  $query = isset($parse_url['query']) ? $parse_url['query'] : '';
  $params = parseUrlParam($query);
  foreach( $cparams as $key => $value ){
      $params[$key] = $value;
  }
  return $parse_url['path'].'?'.http_build_query($params);
  }
  //获取URL参数
  function getUrlParam($cparam, $url = ''){
  $parse_url = $url === '' ? parse_url($_SERVER["REQUEST_URI"]) : parse_url($url);
  $query = isset($parse_url['query']) ? $parse_url['query'] : '';
  $params = parseUrlParam($query);
  return isset($params[$cparam]) ? $params[$cparam] : '';
  }
  $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
  //获取图库数据
  $c_result = getUrlParam('c', $url); //获取图库类别
  $f_result = getUrlParam('f', $url); //获取来源
  $s_result = getUrlParam('s', $url); //获取图片尺寸信息
  if ("$c_result"=="")
  {
  echo "必填参数：图库类别 ";

  exit;
  }
  //补充缺省值
  if ("$f_result"=="")
  {
  $f_result = 'sina';
  }
  if ("$s_result"=="")
  {
  $s_result = 'large';
  }
  $filename=($c_result.'_'.$f_result.'_'.$s_result.'.txt');
  $bool=file_exists($filename);
  //判断图库是否存在
  if ($bool === true)
  {
  //记录调用情况`
    $stat = fopen("stat.txt","r");
  $num = fread($stat,filesize("stat.txt"))  or die("Unable to open file!");
  $nump = $num +1 ;
  $new = fopen("stat.txt", "w") or die("Unable to open file!");
  fwrite($new,$nump);
  fclose($new);
//返回图片链接
$img=file($c_result.'_'.$f_result.'_'.$s_result.'.txt');
$imgurl=array_rand($img);
header("Location:".$img[$imgurl]);

}
else
{
   echo "未找到图库！请检查图库名称和参数。";
   echo "错误信息：".$filename."无法找到。";
}
?>