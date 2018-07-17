<?php
$a = "12344";
$b = "453453454456353456";

function mySum($a,$b){
    if(strlen($a) > strlen($b)){
        $aArr = str_split(strrev($a));
        $bArr = str_split(strrev($b));
    } else {
        $aArr = str_split(strrev($b));
        $bArr = str_split(strrev($a));
    }
    
    $result = [];
    $dec = 0;
    foreach($aArr as $k=>$d){
        
        if(isset($bArr[$k])){
            $sum = $aArr[$k] + $bArr[$k];
        } else {
            $sum = $aArr[$k];
        }
        
        if($dec == 1){
            $sum++;
            $dec=0;
        }
    
        if(strlen($sum) == 2){
            $dec = 1;
            $sum=$sum-10;
        }
        
        $result[$k] = $sum;
    }
    
    return  strrev(implode('',$result));
}

function mySum2($a,$b){
    $aLen = strlen($a);
    $bLen = strlen($b);
    if($aLen > $bLen){
        $aArr = strrev($a);
        $bArr = strrev($b);
    } else {
        $cLen = $bLen;
        $bLen = $aLen;
        $aLen = $cLen;
        $aArr = strrev($b);
        $bArr = strrev($a);
    }
    $result = '';
    
    $dec = 0;
    
    for($k=0;$k<$aLen;$k++){
        if($k<$bLen){
            $sum = $aArr{$k} + $bArr{$k};
        } else {
            $sum = $aArr{$k};
        }
        
        if($dec == 1){
            $sum++;
            $dec=0;
        }
    
        if(strlen($sum) == 2){
            $dec = 1;
            $sum=$sum-10;
        }
        
        $result.= $sum;
    }
    
    return  strrev($result);
}

echo $a+$b;
echo '<br />';
(float)$time = microtime(1);
echo mySum($a,$b);
echo '<br />';
(float)$time1 = microtime(1);
echo 'mySum:'.(float)($time1 - $time);
echo '<br />';
echo mySum2($a,$b);
echo '<br />';
(float)$time2 = microtime(1);
echo 'mySum2:'.(float)($time2 - $time1);
?>