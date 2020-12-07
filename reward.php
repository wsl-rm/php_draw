<?php

class reward
{
    public $rewardMoney;  # 红包总金额
    public $rewardNum;    # 红包数量
    public $scatter;      # 随机散度（1-10000）
    public $rewardArr;    # 红包集合

    public function __construct()
    {
        $this->rewardArr = array();
    }

    public function splintReward($rewardMoney, $rewardNum, $scatter = 100)
    {
        $this->rewardMoney = $rewardMoney;
        $this->rewardNum = $rewardNum;
        $this->scatter = $scatter;
        $this->realscatter = $this->scatter / 100;

        /**
         * 1.算出实际红包值集合的平均率
         * 2.生成随机红包值集合，得出该集合平均率，然后根据该集合值对比实际红包的集合的平均率 算出 实际平均红包值集合
         * 3.分配展示红包集合列表
         */
        # 实际红包集合平均率
        $avgRand = round(1 / $rewardNum, 4);

        # 生成随机红包集合
        $rewardRandArr = array();
        while (count($rewardRandArr) < $rewardNum) {
            $rewardRandArr[] = round(sqrt(mt_rand(1, 10000) / $this->realscatter));
        }

        #计算当前生成的随机数的平均值，保留4位小数
        $randAll = round(array_sum($rewardRandArr) / count($rewardRandArr), 4);

        #为将生成的随机数的平均值变成我们要的1/N，计算一下生成的每个随机数都需要除以的值。我们可以在最后一个红包进行单独处理，所以此处可约等于处理。
        $mixrand = round($randAll / $avgRand, 4);

        # 得出实际红包集合
        sort($rewardRandArr);
        $rewardArrs = array();
        foreach ($rewardRandArr as $v) {
            $t = round($v / $mixrand, 4);
            $rewardArrs[] = round($rewardMoney * $t, 2);
        }

        # 重新计算最大的一个红包值
        $rewardArrs[$this->rewardNum - 1] = $this->rewardMoney - (array_sum($rewardArrs) - $rewardArrs[$this->rewardNum - 1]);

        # 排序红包集合
        rsort($rewardArrs);
        foreach ($rewardArrs as $k => $v) {
            if ($k % 2) array_push($this->rewardArr, $v);
            else array_unshift($this->rewardArr, $v);
        }
        return $this->rewardArr;
    }
}

# 随机红包生成
$rewardMoney = 1000;
$rewardNum = 10;
$rewardM = new reward();
$rewardArr = $rewardM->splintReward($rewardMoney, $rewardNum);

echo "发放红包个数：{$rewardNum}，红包总金额{$rewardMoney}元。下方所有红包总额之和：" . array_sum($rewardM->rewardArr) . '元。下方用图展示红包的分布';
echo '<hr>';
echo "<table style='font-size:12px;width:600px;border:1px solid #ccc;text-align:left;'><tr><td>红包金额</td><td>图示</td></tr>";
foreach ($rewardArr as $val) {
    #线条长度计算
    $width = intval($rewardNum * $val * 300 / $rewardMoney);
    echo "<tr><td>{$val}</td><td width='500px;text-align:left;'><hr style='width:{$width}px;height:3px;border:none;border-top:3px double red;margin:0 auto 0 0px;'></td></tr>";
}
echo "</table>";
