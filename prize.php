<?php

class prize
{
    # 方式一
    public function get_rand($proArr)
    {
        $result = array();
        foreach ($proArr as $key => $val) {
            $arr[$key] = $val['v'];
        }
        // 概率数组的总概率
        $proSum = array_sum($arr);
        asort($arr);
        // 概率数组循环
        foreach ($arr as $k => $v) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $v) {
                $result = $proArr[$k];
                break;
            } else {
                $proSum -= $v;
            }
        }
        return $result;
    }

    # 方式二
    public function getRand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($proArr);
        return $result;
    }
}

$arr = array(
    array('id' => 1, 'name' => '特等奖', 'v' => 1),
    array('id' => 2, 'name' => '一等奖', 'v' => 5),
    array('id' => 3, 'name' => '二等奖', 'v' => 10),
    array('id' => 4, 'name' => '三等奖', 'v' => 12),
    array('id' => 5, 'name' => '四等奖', 'v' => 22),
    array('id' => 6, 'name' => '没中奖', 'v' => 50)
);

# 方式二
foreach ($arr as $val) {
    $item[$val['id']] = $val['v'];
}

$prizeM = new prize();
var_dump($prizeM->getRand($item));
echo "<br>";
var_dump($arr[$prizeM->getRand($item) - 1]);
echo "<hr>";

# 方式一
var_dump($prizeM->get_rand($arr));
