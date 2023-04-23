<?php

namespace shudu\models;

use Exception;

class Logic extends \yii\base\BaseObject
{
    const ROW = 'Row';
    const COL = 'Col';
    const GRID = 'Grid';

    const EX_EXL = 'exl';
    const EX_VIP = 'vip';
    const EX_INP = 'inp';
    const EX_XWING = 'xwing';

    const NONE = 0;
    const COMPARE = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    // 原始数据
    public $rawData = [];
    // 各维度最终数据
    protected $rowData = [];
    protected $colData = [];
    protected $gridData = [];
    // 各维度笔记
    protected $tagRowData = [];
    protected $tagColData = [];
    protected $tagGridData = [];
    // 各方法解答记录
    protected $exclusionData = [];
    protected $visibleData = [];
    protected $hideData = [];
    protected $xwingData = [];
    // 解题使用的方法
    protected $methods = [];

    public function init()
    {
        parent::init();

        foreach ($this->rawData as $row => $value) {
            foreach ($value as $col => $v) {
                $this->setNumber($row, $col, $v);
            }
        }
        $this->addTags();
    }

    public function checkRawData($data)
    {
        $data = preg_replace('/[\D]*/', '', $data);
        if (strlen($data) != 81) {
            return false;
        }
        $data = str_split($data);
        array_walk($data, 'intval');
        $this->rawData = array_chunk($data, 9);
        $this->init();
        return true;
    }

    public function validate(&$errors = [])
    {
        $validate = function ($data, $type, &$errors) {
            $return = true;
            foreach ($data as $key => $value) {
                $ret = array_diff(self::COMPARE, $value);
                if (count($ret) > 0) {
                    $return = false;
                    $errors[$type][$key] = array_values($ret);
                }
            }
            return $return;
        };
        $r = $validate($this->rowData, self::ROW, $errors);
        $r = $r && $validate($this->colData, self::COL, $errors);
        $r = $r && $validate($this->gridData, self::GRID, $errors);
        return $r;
    }

    public function getResult()
    {
        return new Result($this->rawData, $this->rowData, $this->tagRowData, $this->methods);
    }

    public function setMethods($methods = [])
    {
        $this->methods = array_flip($methods);
    }

    private function isFail()
    {
        foreach ($this->rowData as $row => $data) {
            foreach ($data as $col => $v) {
                if (empty($v) && empty($this->tagRowData[$row][$col])) {
                    return true;
                }
            }
        }
        return false;
    }

    private function isOver()
    {
        foreach ($this->rowData as $data) {
            if (in_array(0, $data)) {
                return false;
            }
        }
        return true;
    }

    public function solve($step = -1)
    {
        $continue = true;
        $n = 0;
        $answer = 0;
        $undo = 0;
        do {
            $num = $this->chooseOnly();
            if (isset($this->methods[self::EX_EXL])) {
                $this->solveByExclusion();
            }
            if (isset($this->methods[self::EX_VIP])) {
                $this->solveByVisiblePair();
            }
            if (isset($this->methods[self::EX_INP])) {
                $this->solveByHidePair();
            }
            if (isset($this->methods[self::EX_XWING])) {
                $this->solveByXWing();
            }
            $answer += $num;
            $n++;
            if ($step < 0) {
                if ($num == 0) {
                    if ($undo >= 2) {
                        $continue = false;
                    } else {
                        $undo++;
                    }
                } else {
                    $undo = 0;
                }
            } else {
                if ($n >= $step) {
                    $continue = false;
                }
            }
            if ($this->isFail()) {
                $continue = false;
            }
            if ($this->isOver()) {
                $continue = false;
            }
        } while ($continue && $n < 20);
        $result = $this->getResult();
        $result->setCount($n, count($this->exclusionData), count($this->visibleData), count($this->hideData), count($this->xwingData), $answer);
        return $result;
    }

    public function addTags()
    {
        foreach ($this->rowData as $row => $value) {
            foreach ($value as $col => $v) {
                if ($v == self::NONE) {
                    $this->tagRowData[$row][$col] = array_diff(self::COMPARE, $value);
                    $this->tagRowData[$row][$col] = array_intersect($this->tagRowData[$row][$col], array_diff(self::COMPARE, $this->colData[$this->getColRow($row, $col)]));
                    $this->tagRowData[$row][$col] = array_intersect($this->tagRowData[$row][$col], array_diff(self::COMPARE, $this->gridData[$this->getGridRow($row, $col)]));
                    $this->setTag($row, $col, $this->tagRowData[$row][$col]);
                }
            }
        }
        ksort($this->tagColData);
    }

    private function setNumber($row, $col, $number)
    {
        $this->rowData[$row][$col] = $number;
        list($r, $c) = $this->getColPos($row, $col);
        $this->colData[$r][$c] = $number;
        list($r, $c) = $this->getGridPos($row, $col);
        $this->gridData[$r][$c] = $number;
    }

    /**
     * 根据行级坐标，同时消除各个维度的笔记数据
     */
    public function unsetTag($row, $col, $number, $type = self::ROW, $callback = null)
    {
        if ($type == self::COL) {
            list($row, $col) = $this->getColPos($row, $col);
        } elseif ($type == self::GRID) {
            list($row, $col) = $this->getGridPos($row, $col);
        }
        list($r1, $c1) = $this->getColPos($row, $col);
        list($r2, $c2) = $this->getGridPos($row, $col);
        $this->tagRowData[$row][$col] = array_diff($this->tagRowData[$row][$col], [$number]);
        $this->tagColData[$r1][$c1] = array_diff($this->tagColData[$r1][$c1], [$number]);
        $this->tagGridData[$r2][$c2] = array_diff($this->tagGridData[$r2][$c2], [$number]);
        // 事后行为，参数为行级坐标与消除的数字
        if (is_callable($callback)) {
            call_user_func($callback, $row, $col, $number);
        }
    }

    private function setTag($row, $col, $item)
    {
        list($r1, $c1) = $this->getColPos($row, $col);
        list($r2, $c2) = $this->getGridPos($row, $col);
        if ($item === 0) {
            unset($this->tagRowData[$row][$col]);
            unset($this->tagColData[$r1][$c1]);
            unset($this->tagGridData[$r2][$c2]);
        } else {
            $this->tagColData[$r1][$c1] = $item;
            $this->tagGridData[$r2][$c2] = $item;
        }
    }

    // 唯一选择
    private function chooseOnly()
    {
        $success = 0;
        $solve = function ($data, $type, &$success) {
            $chooseNumber = function ($row, $col, $number) {
                if ($this->rowData[$row][$col] != self::NONE) {
                    return false;
                }
                $this->setNumber($row, $col, $number);
                $this->setTag($row, $col, 0);
                return true;
            };
            foreach ($data as $row => $value) {
                foreach ($value as $col => $v) {
                    if (count($data[$row][$col]) == 1) {
                        switch ($type) {
                            case self::ROW:
                                $r = $chooseNumber($row, $col, current($v));
                                break;
                            case self::COL:
                                list($r, $c) = $this->getColPos($row, $col);
                                $r = $chooseNumber($r, $c, current($v));
                                break;
                            case self::GRID:
                                list($r, $c) = $this->getGridPos($row, $col);
                                $r = $chooseNumber($r, $c, current($v));
                                break;
                        }
                        if ($r == true) {
                            $success++;
                        }
                    }
                }
            }
        };
        $solve($this->tagRowData, self::ROW, $success);
        $solve($this->tagColData, self::COL, $success);
        $solve($this->tagGridData, self::GRID, $success);
        if ($success > 0) {
            $this->addTags();
        }
        return $success;
    }

    // 宫线排除，集成排除数对
    public function solveByExclusion()
    {
        $solve = function ($data, $type) {
            foreach ($data as $row => $value) {
                // 获取当前行对应宫数据
                $record = [];
                foreach ($value as $col => $numbers) {
                    $gridRow = $this->getGridRow($row, $col, $type);
                    if (!isset($record[$gridRow])) {
                        $record[$gridRow] = [];
                    }
                    $record[$gridRow] = array_unique(array_merge($record[$gridRow], $numbers));
                }
                //-------------------------------------------------
                //                以下为排除数对
                //-------------------------------------------------
                foreach ($record as $gridRow => $numbers) {
                    // 找到仅存于该行的笔记
                    $pairRecord = $numbers;
                    foreach ($this->tagGridData[$gridRow] as $gridCol => $gridData) {
                        if (empty($pairRecord)) {
                            break;
                        }
                        // 将当前宫坐标转换为行坐标
                        list($r, $c) = $this->getGridPos($gridRow, $gridCol);
                        if ($type == self::COL) {
                            $r = $this->getColRow($r, $c);
                        }
                        if ($r != $row) {
                            $intersect = array_intersect($numbers, $gridData);
                            $pairRecord = array_diff($pairRecord, $intersect);
                        }
                    }
                    if (!empty($pairRecord)) {
                        // 消除该行中，其他宫的该数字
                        foreach ($value as $col => $numbers) {
                            // 将当前行数据转换为宫坐标
                            $gr = $this->getGridRow($row, $col, $type);
                            if ($gr != $gridRow) {
                                foreach ($pairRecord as $n) {
                                    if (in_array($n, $numbers)) {
                                        $this->unsetTag($row, $col, $n, $type, function ($row, $col, $number) use ($type) {
                                            $this->exclusionData[$this->getKey([$type, $row, $col])] = $number;
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
                //-------------------------------------------------
                //                以下为宫线排除
                //-------------------------------------------------
                // 初始化获得宫数据的每个数字的出现次数为1
                $countRecord = [];
                foreach ($record as $gridRow => $numbers) {
                    $countRecord[$gridRow] = array_count_values($numbers);
                }
                // 每个宫依次与别的宫进行交集比较，如果产生交集，则表示该行该数存在多个
                foreach ($countRecord as $k1 => $v1) {
                    foreach ($countRecord as $k2 => $v2) {
                        if ($k1 >= $k2) {
                            continue;
                        }
                        $intersectKeys = array_intersect_key($v1, $v2);
                        foreach ($intersectKeys as $k => $n) {
                            $countRecord[$k1][$k]++;
                            $countRecord[$k2][$k]++;
                        }
                    }
                }
                // 如果该数字只在当前宫出现，则排除该宫的其他位置的该数字笔记
                foreach ($countRecord as $gridRow => $item) {
                    foreach ($item as $number => $count) {
                        if ($count == 1) {
                            foreach ($this->tagGridData[$gridRow] as $gridCol => $val) {
                                // 将宫坐标转换为行坐标
                                list($rowPos, $colPos) = $this->getGridPos($gridRow, $gridCol);
                                if ($type == self::COL) {
                                    list($tr, $tc) = $this->getColPos($rowPos, $colPos);
                                } else {
                                    list($tr, $tc) = [$rowPos, $colPos];
                                }
                                if ($tr != $row && in_array($number, $val)) {
                                    $this->unsetTag($rowPos, $colPos, $number, self::ROW, function ($row, $col, $number) {
                                        $this->exclusionData[$this->getKey([$row, $col])] = $number;
                                    });
                                }
                            }
                        }
                    }
                }
            }
        };
        $solve($this->tagRowData, self::ROW);
        $solve($this->tagColData, self::COL);
    }

    // 显性数对
    public function solveByVisiblePair()
    {
        $solve = function ($data, $row, $type) {
            $record = [];
            foreach ($data as $item) {
                $key = $this->getKey($item);
                if (!isset($record[$key])) {
                    $record[$key] = 0;
                }
                foreach ($data as $sub) {
                    $union = array_unique(array_merge($item, $sub));
                    sort($union);
                    $unionKey = $this->getKey($union);
                    if (!isset($record[$unionKey])) {
                        $record[$unionKey] = 0;
                    }
                }
            }
            foreach ($record as $k => $num) {
                foreach ($data as $item) {
                    $base = $this->resetKey($k);
                    if ($this->inArray($base, $item)) {
                        $record[$k]++;
                    }
                }
            }
            foreach ($record as $k => $num) {
                $pieces = $this->resetKey($k);
                if (count($pieces) == $num) {
                    foreach ($data as $col => $item) {
                        if (!$this->inArray($pieces, $item)) {
                            $before = $data[$col];
                            $data[$col] = array_diff($item, $pieces);
                            if (count($before) != count($data[$col]) && count($pieces) > 1) {
                                $remove = array_intersect($item, $pieces);
                                foreach ($remove as $v) {
                                    $this->unsetTag($row, $col, $v, $type, function ($row, $col, $number) use ($type) {
                                        $this->visibleData[$this->getKey([$type, $row, $col])] = $number;
                                    });
                                }
                            }
                        }
                    }
                }
            }
            return $data;
        };
        foreach ($this->tagRowData as $key => $value) {
            $this->tagRowData[$key] = $solve($value, $key, self::ROW);
        }
        foreach ($this->tagColData as $key => $value) {
            $this->tagColData[$key] = $solve($value, $key, self::COL);
        }
        foreach ($this->tagGridData as $key => $value) {
            $this->tagGridData[$key] = $solve($value, $key, self::GRID);
        }
    }

    // 隐性数对
    public function solveByHidePair()
    {
        $solve = function ($data, $type) {
            $record = [];
            $split = function ($array) {
                $return = [];
                foreach ($array as $value) {
                    foreach ($return as $v) {
                        $return[] = array_unique(array_merge($v, [$value]));
                    }
                    $return[] = [$value];
                }
                return $return;
            };
            foreach ($data as $item) {
                $pieces = $split($item);
                foreach ($pieces as $arr) {
                    $k = $this->getKey($arr);
                    if (!isset($record[$k])) {
                        $record[$k] = 0;
                    }
                }
            }
            foreach ($record as $key => $num) {
                $pieces = $this->resetKey($key);
                foreach ($data as $item) {
                    $intersect = array_intersect($pieces, $item);
                    if (count($intersect) > 0) {
                        $record[$key]++;
                    }
                }
            }
            foreach ($record as $key => $num) {
                $pieces = $this->resetKey($key);
                if (count($pieces) == $num) {
                    foreach ($data as $i => $item) {
                        if ($this->inArray($item, $pieces) && count($item) != count($pieces)) {
                            $data[$i] = array_intersect($item, $pieces);
                            $this->hideData[$this->getKey([$type, $i, $key])] = $pieces;
                        }
                    }
                }
            }
            return $data;
        };
        foreach ($this->tagRowData as $key => $value) {
            $this->tagRowData[$key] = $solve($value, self::ROW);
        }
        foreach ($this->tagColData as $key => $value) {
            $this->tagColData[$key] = $solve($value, self::COL);
        }
        foreach ($this->tagGridData as $key => $value) {
            $this->tagGridData[$key] = $solve($value, self::GRID);
        }
    }

    // X翼
    public function solveByXWing()
    {
        $xwing = new XWingSolution(function ($rowData, $gruop, $number, $type) {
            foreach ($gruop as $row => $cols) {
                foreach ($rowData[$row] as $col => $data) {
                    if (!in_array($col, $cols) && in_array($number, $data)) {
                        $this->unsetTag($row, $col, $number, $type, function ($row, $col, $number) use ($type) {
                            $this->xwingData[$this->getKey([$type, $row, $col])] = $number;
                        });
                    }
                }
            }
        });
        $xwing->initData($this->tagRowData, $this->tagColData, self::ROW);
        $xwing->solveXWing();

        $xwing->initData($this->tagColData, $this->tagRowData, self::COL);
        $xwing->solveXWing();

        $xwing->initData($this->tagRowData, $this->tagColData, self::ROW);
        $xwing->solveXFish();

        $xwing->initData($this->tagColData, $this->tagRowData, self::COL);
        $xwing->solveXFish();
    }

    private function getKey($array)
    {
        return implode('-', $array);
    }

    private function resetKey($string)
    {
        return explode('-', $string);
    }

    private function inArray($base, $target)
    {
        $flag = true;
        foreach ($target as $n) {
            if (!in_array($n, $base)) {
                $flag = false;
                break;
            }
        }
        return $flag;
    }

    private function getColRow($row, $col)
    {
        return $this->getColPos($row, $col)[0];
    }

    private function getGridRow($row, $col, $type = self::ROW)
    {
        if ($type == self::COL) {
            list($row, $col) = $this->getColPos($row, $col);
        }
        return $this->getGridPos($row, $col)[0];
    }

    /**
     * 行坐标与列坐标的相互转换
     */
    private function getColPos($row, $col)
    {
        return [$col, $row];
    }

    /**
     * 行坐标与宫坐标相互转换
     */
    private function getGridPos($row, $col, $type = self::ROW)
    {
        if ($type == self::COL) {
            list($row, $col) = $this->getColPos($row, $col);
        }
        return [floor($row / 3) * 3 + floor($col / 3), floor($row % 3) * 3 + floor($col % 3)];
    }
}

class XWingSolution
{
    private $rowData;
    private $colData;
    private $type;
    private $unsetCallback;

    public function __construct($unsetCallback)
    {
        $this->unsetCallback = $unsetCallback;
    }

    public function initData($rowData, $colData, $type)
    {
        $this->rowData = $rowData;
        $this->colData = $colData;
        $this->type = $type;
    }

    public function solveXFish()
    {
        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        foreach (array_keys($this->colData) as $col) {
            foreach ($numbers as $number) {
                $this->solveFishCol($col, $number);
            }
        }
    }

    private function solveFishCol($col, $number)
    {
        $group = $this->findFishCol($col, $number);
        if ($group !== false) {
            // 找到剩下两列数据
            $this->findNextCol($group, $col, $number, 1);
        }
    }

    private function findNextCol($group, $col, $number, $restColNum)
    {
        foreach (array_keys($this->colData) as $c) {
            if ($c > $col) {
                $foundGroup = $this->findFishCol($c, $number);
                if ($foundGroup !== false) {
                    if ($restColNum > 0) {
                        $nextGroup = $this->mergeGroup($group, $foundGroup);
                        $this->findNextCol($nextGroup, $c, $number, $restColNum - 1);
                    } else {
                        $finalGroup = $this->mergeGroup($group, $foundGroup);
                        if ($this->checkFishGroup($finalGroup)) {
                            call_user_func($this->unsetCallback, $this->rowData, $finalGroup, $number, $this->type);
                        }
                    }
                }
            }
        }
    }
    
    private function findFishCol($col, $number)
    {
        $count = 0;
        $return = [];
        foreach ($this->colData[$col] as $row => $tags) {
            if (in_array($number, $tags)) {
                $count++;
                $return[$row][] = $col;
            }
        }
        if ($count == 2 || $count == 3) {
            return $return;
        } else {
            return false;
        }
    }

    /**
     * ```
     * $group = [
     *     0 => [1, 5, 8],
     *     4 => [1, 5, 8],
     *     7 => [1, 5, 8]
     * ]
     * ```
     */
    private function checkFishGroup($group)
    {
        if (count($group) != 3) {
            return false;
        }
        $record = [];
        foreach ($group as $row => $value) {
            $group[$row] = array_unique($value);
            $count = count($group[$row]);
            if ($count > 3 || $count < 2) {
                return false;
            }
            foreach ($group[$row] as $v) {
                $record[$v] = $record[$v] ?? 0;
                $record[$v]++;
            }
        }
        if (count($record) != 3) {
            return false;
        }
        foreach ($record as $c) {
            if ($c < 2) {
                return false;
            }
        }
        return true;
    }

    private function mergeGroup($group1, $group2)
    {
        foreach ($group2 as $key => $value) {
            if (isset($group1[$key])) {
                $group1[$key] = array_merge($group1[$key], $value);
            } else {
                $group1[$key] = $value;
            }
        }
        return $group1;
    }

    public function solveXWing()
    {
        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        foreach (array_keys($this->colData) as $col) {
            foreach ($numbers as $number) {
                $this->solveCol($col, $number);
            }
        }
    }

    /**
     * @param int   $col    为从0开始计数的列号
     * @param int   $number 表示当前计算的数字
     */
    private function solveCol($col, $number)
    {
        $group = $this->findCol($col, $number);
        // 每列必须只有2个笔记数
        if ($group !== false) {
            $group = $this->findRow($group, $number);
            if ($group !== false && $this->checkGroup($group)) {
                call_user_func($this->unsetCallback, $this->rowData, $group, $number, $this->type);
            }
        }
    }

    /**
     * @param array $group 此时格式如下
     * ```
     * $group = [
     *     0 => [1],
     *     4 => [1]
     * ]
     * ```
     */
    private function findRow($group, $number)
    {
        $rows = array_keys($group);
        $currentRow = key($group);
        $currentCol = current(current($group));
        foreach ($this->rowData[$currentRow] as $col => $tags) {
            if ($col > $currentCol && in_array($number, $tags)) {
                $ret = $this->findCol($col, $number);
                if ($ret !== false) {
                    $targetRows = array_keys($ret);
                    if ($rows == $targetRows) {
                        foreach ($group as $k => $v) {
                            $group[$k] = array_merge($v, $ret[$k]);
                        }
                        return $group;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param  int   $col   当前列
     * @return array|bool
     */
    private function findCol($col, $number)
    {
        $count = 0;
        $return = [];
        foreach ($this->colData[$col] as $row => $tags) {
            if (in_array($number, $tags)) {
                $count++;
                $return[$row][] = $col;
            }
        }
        if ($count == 2) {
            return $return;
        } else {
            return false;
        }
    }

    /**
     * ```
     * $group = [
     *     0 => [1, 5],
     *     4 => [1, 5]
     * ]
     * ```
     */
    private function checkGroup($group)
    {
        if (count($group) == 2) {
            foreach ($group as $v) {
                if (count($v) != 2) {
                    return false;
                }
            }
            $values = array_values($group);
            return count(array_diff(...$values)) == 0;
        } else {
            return false;
        }
    }
}

class Result
{
    public $raw;
    public $data;
    public $tag;
    public $methods;
    public $step = 0;

    private $g;
    private $v;
    private $h;
    private $x;
    private $e;

    public function __construct($raw, $data, $tag, $methods)
    {
        $this->raw = $raw;
        $this->data = $data;
        $this->tag = $tag;
        $this->methods = $methods;
    }

    public function setCount($step, $g, $v, $h, $x, $e)
    {
        $this->step = $step;
        $this->g = $g;
        $this->v = $v;
        $this->h = $h;
        $this->x = $x;
        $this->e = $e;
    }

    public function getDesc()
    {
        $template = '当前探索 %d 次';
        $args = [$this->step];
        if (isset($this->methods[Logic::EX_EXL])) {
            $template .= '，宫线排除 %d 个';
            $args[] = $this->g;
        }
        if (isset($this->methods[Logic::EX_VIP])) {
            $template .= '，显性数对 %d 个';
            $args[] = $this->v;
        }
        if (isset($this->methods[Logic::EX_INP])) {
            $template .= '，隐性数对 %d 个';
            $args[] = $this->h;
        }
        if (isset($this->methods[Logic::EX_XWING])) {
            $template .= '，X翼 %d 个';
            $args[] = $this->x;
        }
        $template .= '，解答数 %d 个';
        $args[] = $this->e;
        return sprintf($template, ...$args);
    }
}
