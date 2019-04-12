<?php

namespace common\helpers;

use Yii;

class Excel
{
    /**
     * 读取EXCEL内容，返回一个二维数组
     * 
     * @param  string   $path                   EXCEL文件的物理路径
     * @param  array    $options                 配置参数：
     *                                          * columns: 数组形式，要读取的列标题，默认全部列.
     *                                          * sheet: 要读取的sheet，默认sheet1
     *                                          * cellCallback: 每个单元格格式化方法，第一个参数表示每个单元格的值，第二个参数表示所在的列，第三个参数表示所在行
     *                                          * rowCallback: 获取到每行数据后的回调方法，第一个参数表示该行的所有数据，第二个参数表示所在行，当开启此参数后，不再返回结果
     * @return array                            返回类似数据库结果集的二维数组
     */
    public static function readExcel($path, $options = [])
    {
        require Yii::getAlias('@vendor/PHPExcel/Classes/PHPExcel.php');

        $PHPReader = FileHelper::getExt($path) == 'xls' ? new \PHPExcel_Reader_Excel5 : new \PHPExcel_Reader_Excel2007;
        $PHPExcel = $PHPReader->load($path);

        $sheet = isset($options['sheet']) ? $options['sheet'] - 1 : 0;
        if (isset($options['rowCallback'])) {
            if (isset($options['cellCallback'])) {
                $evalCellStr = '$return[] = call_user_func($options["cellCallback"], $val, $col + 1, $row - 1);';
            } else {
                $evalCellStr = '$return[] = $val;';
            }
            $evalRowStr = 'call_user_func($options["rowCallback"], $return, $row - 1);$return = [];';
        } else {
            if (isset($options['cellCallback'])) {
                $evalCellStr = '$return[$row - 1][] = call_user_func($options["cellCallback"], $val, $col + 1, $row - 1);';
            } else {
                $evalCellStr = '$return[$row - 1][] = $val;';
            }
            $evalRowStr = '';
        }
        if (isset($options['columns'])) {
            $evalIfstr1 = '$ifStr = array_key_exists($val, $options[\'columns\']);';
            $options['columns'] = array_flip($options['columns']);
        } else {
            $evalIfstr1 = '$ifStr = true;';
        }

        $sheet = $PHPExcel->getSheet($sheet);
        $allRow = $sheet->getHighestRow();
        $allCol = $sheet->getHighestColumn();
        $loadTh = $loadTd = 0;
        $title = [];
        $return = [];
        for ($row = 1; $row <= $allRow; $row++) {
            for ($col = ord('A') - 65; $col <= ord($allCol) - 65; $col++) {
                $val = trim($sheet->getCellByColumnAndRow($col, $row)->getValue());
                eval($evalIfstr1);
                if ($ifStr) {
                    $loadTh = 1;
                    $title[$col] = $val;
                }
                if ($loadTd === 1) {
                    if (isset($title[$col])) {
                        eval($evalCellStr);
                    }
                }
            }
            if ($loadTd === 1) {
                eval($evalRowStr);
            }
            if ($loadTh === 1) {
                $loadTh = 0;
                $loadTd = 1;
            }
        }
        if (isset($options['rowCallback'])) {
            return;
        } else {
            return $return;
        }
    }

    /**
     * 设置Excel列标题
     * 
     * @param object $sheet  PHPExcel的sheet对象
     * @param array  $titles 列标题数组
     */
    public static function setTitles($sheet, $titles)
    {
        foreach ($titles as $col => $title) {
            $cell = self::getCell($col, 1);
            $sheet->setCellValueExplicit($cell, $title);
            // 字体颜色
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF5511');
            // 字体加粗
            $sheet->getStyle($cell)->getFont()->setBold(true);
            // 垂直居中
            $sheet->getStyle($cell)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            // 水平居中
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
    }

    /**
     * 设置Excel列内容
     * 
     * @param object $sheet    PHPExcel的sheet对象
     * @param array  $contents 列内容数组
     * @param int    $row      行数
     */
    public static function setContents($sheet, $contents, $row)
    {
        foreach ($contents as $col => $content) {
            $sheet->setCellValueExplicit(self::getCell($col, $row), strip_tags($content));
        }
    }

    /**
     * 获得Excel单元格坐标
     * 
     * @param  int $x 从0开始计数的横向坐标
     * @param  int $y 从1开始计数的行数
     * @return string
     */
    protected static function getCell($x, $y)
    {
        $ret = '';
        do {
            $mod = $x % 26;
            $x = (int) ($x / 26);
            $ret = chr($mod + 65) . $ret;
        } while ($x-- > 0);
        return $ret . $y;
    }
}
