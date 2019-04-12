<?php

namespace common\helpers;

use Yii;

class Debug
{
    private static $flag = 1;

    private static $_microtime = [];

    private static $_memory = [];

    public static function sqlList()
    {
        $list = [];
        $time = $lastTime = 0;

        foreach (Yii::getLogger()->getProfiling(['yii\db\Command::query', 'yii\db\Command::execute']) as $record) {
            $forbidList = ['information_schema.referential_constraints', 'common_settings', 'SHOW FULL COLUMNS'];
            foreach ($forbidList as $string) {
                if (strpos($record['info'], $string) !== false) {
                    continue 2;
                }
            }

            $diff = $lastTime === 0 ? 0 : $record['timestamp'] - $lastTime;
            $time += $diff + $record['duration'];
            $lastTime = $record['timestamp'];
            $row = [
                'sql' => $record['info'],
                'category' => $record['category'],
                'duration' => round($record['duration'] * 1000, 2),
                'diff' => round($diff * 1000, 2),
                'time' => round($time * 1000, 2)
            ];
            if (YII_DEBUG) {
                $row['trace'] = $record['trace'][0]['file'] . ':' . $record['trace'][0]['line'];
            } else {
                $row['trace'] = '';
            }
            $list[] = $row;
        }

        return $list;
    }

    public static function log($tagName = '', $swithName = '')
    {
        if ($swithName && !defined($swithName)) {
            return;
        }
        $flag = str_pad(self::$flag++, 3, '0', STR_PAD_LEFT) . '. ';
        if ($tagName) {
            $tagName = ' -> ' . $tagName;
        }
        $memory = memory_get_usage(true);
        $microtime = microtime(true);
        $lastMemory = end(self::$_memory);
        $lastTime = end(self::$_microtime);
        self::$_memory[] = $memory;
        self::$_microtime[] = $microtime;
        $diffMemory = $lastMemory ? 'diffMemory：' . self::formatMemory($memory - $lastMemory) : '';
        $diffTime = $lastTime ? 'diffTime：' . number_format(($microtime - $lastTime), 3) * 1000 . '(ms)' : '';
        $eol = PHP_SAPI === 'cli' ? PHP_EOL : '<br>';
        $items = [
            'nowTime：' . $microtime,
            'nowMemory：' . self::formatMemory($memory)
        ];
        if ($diffTime) {
            $items[] = $diffTime;
        }
        if ($diffMemory) {
            $items[] = $diffMemory;
        }
        echo "{$flag}" . implode('，', $items) . "{$tagName}{$eol}";
    }

    private static function formatMemory($memoryUsage)
    {
        if ($memoryUsage < 1024) {
            return $memoryUsage . '(bytes)';
        } elseif ($memoryUsage < 1024 * 1024) {
            return round($memoryUsage / 1024, 3) . '(KB)';
        } else {
            return round($memoryUsage / 1024 / 1024, 3) . '(MB)';
        }
    }
}
