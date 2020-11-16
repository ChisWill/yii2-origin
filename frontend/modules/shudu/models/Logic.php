<?php

namespace shudu\models;

class Logic extends \yii\base\Object
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

    public $rawData = [];

    protected $rowData = [];
    protected $colData = [];
    protected $gridData = [];

    protected $tagRowData = [];
    protected $tagColData = [];
    protected $tagGridData = [];

    public $exclusionData = [];
    public $visibleData = [];
    public $hideData = [];
    public $xwingData = [];

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
        foreach ($this->rowData as $row => $value) {
            foreach ($value as $col => $v) {
                if (empty($v) && empty($this->tagRowData[$row][$col])) {
                    return true;
                }
            }
        }
        return false;
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

    public function unsetTag($row, $col, $number)
    {
        list($r1, $c1) = $this->getColPos($row, $col);
        list($r2, $c2) = $this->getGridPos($row, $col);
        $this->tagRowData[$row][$col] = array_diff($this->tagRowData[$row][$col], [$number]);
        $this->tagColData[$r1][$c1] = array_diff($this->tagColData[$r1][$c1], [$number]);
        $this->tagGridData[$r2][$c2] = array_diff($this->tagGridData[$r2][$c2], [$number]);
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

    // 宫线排除
    public function solveByExclusion()
    {
        $solve = function ($data, $type) {
            foreach ($data as $row => $value) {
                $record = [];
                foreach ($value as $col => $val) {
                    $gridRow = $this->getGridRow($row, $col, $type);
                    if (!isset($record[$gridRow])) {
                        $record[$gridRow] = [];
                    }
                    $record[$gridRow] = array_unique(array_merge($record[$gridRow], $val));
                }
                $countRecord = [];
                foreach ($record as $gridRow => $val) {
                    $countRecord[$gridRow] = array_count_values($val);
                }
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
                foreach ($countRecord as $gridRow => $item) {
                    foreach ($item as $number => $count) {
                        if ($count == 1) {
                            foreach ($this->tagGridData[$gridRow] as $gridCol => $val) {
                                list($rr, $rc) = $this->getGridPos($gridRow, $gridCol);
                                if ($type == self::COL) {
                                    list($r, $c) = $this->getColPos($rr, $rc);
                                } else {
                                    list($r, $c) = [$rr, $rc];
                                }
                                if ($r != $row && in_array($number, $val)) {
                                    $this->exclusionData[$this->getKey([$rr, $rc])] = $number;
                                    $this->unsetTag($rr, $rc, $number);
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
                    foreach ($data as $i => $item) {
                        if (!$this->inArray($pieces, $item)) {
                            $before = $data[$i];
                            $data[$i] = array_diff($item, $pieces);
                            if (count($before) != count($data[$i]) && count($pieces) > 1) {
                                $remove = array_intersect($item, $pieces);
                                if ($type == self::ROW) {
                                    foreach ($remove as $v) {
                                        $this->unsetTag($row, $i, $v);
                                    }
                                } elseif ($type == self::COL) {
                                    list($r, $c) = $this->getColPos($row, $i);
                                    foreach ($remove as $v) {
                                        $this->unsetTag($r, $c, $v);
                                    }
                                } elseif ($type == self::GRID) {
                                    list($r, $c) = $this->getGridPos($row, $i);
                                    foreach ($remove as $v) {
                                        $this->unsetTag($r, $c, $v);
                                    }
                                }
                                $this->visibleData[$this->getKey([$type, $i, $k])] = $pieces;
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
        $xwing = new XWingSolution($this, $this->tagRowData, $this->tagColData, self::ROW);
        $xwing->solve();

        $xwing = new XWingSolution($this, $this->tagColData, $this->tagRowData, self::COL);
        $xwing->solve();
    }

    public function getKey($array)
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

    public function getColPos($row, $col)
    {
        return [$col, $row];
    }

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
    /** @var Logic $logic */
    private $logic;
    private $rowData;
    private $colData;
    private $type;

    public function __construct($logic, $rowData, $colData, $type)
    {
        $this->logic = $logic;
        $this->rowData = $rowData;
        $this->colData = $colData;
        $this->type = $type;
    }

    public function solve()
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
                $this->unsetTag($group, $number);
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

    private function unsetTag($gruop, $number)
    {
        foreach ($gruop as $row => $cols) {
            foreach ($this->rowData[$row] as $col => $data) {
                if (!in_array($col, $cols) && in_array($number, $data)) {
                    if ($this->type == Logic::COL) {
                        list($r, $c) = $this->logic->getColPos($row, $col);
                    } else {
                        list($r, $c) = [$row, $col];
                    }
                    $this->logic->xwingData[$this->logic->getKey([$this->type, $r, $c])] = $number;
                    $this->logic->unsetTag($r, $c, $number);
                }
            }
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
