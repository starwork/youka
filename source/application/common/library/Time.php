<?php
/**
 *
 * Created by PhpStorm.
 * User: lc
 * Date: 2019-05-16 16:35
 */

namespace app\common\library;


class Time
{
    /**
     * 今天
     * @return array
     */
    public static function Today()
    {
        $start_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('d'),date('Y'));
        return [$start_time,$end_time];
    }

    /**
     * 昨日
     * @return array
     */
    public static function YesterDay()
    {
        $start_time = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
        return [$start_time,$end_time];
    }

    /**
     * 本周
     * @return array
     */
    public static function Week()
    {
        $start_time = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y'));
        return [$start_time,$end_time];
    }

    /**
     * 上周
     * @return array
     */
    public static function LastWeek()
    {
        $start_time = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
        return [$start_time,$end_time];
    }

    /**
     * 本月
     * @return array
     */
    public static function Month()
    {
        $start_time = mktime(0,0,0,date('m'),1,date('Y'));
        $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));
        return [$start_time,$end_time];
    }

    /**
     * 上月
     * @return array
     */
    public static function LastMonth($num = 0)
    {
        $start_time = mktime(0,0,0,date('m')-$num-1,1,date('Y'));
        $end_time = mktime(23,59,59,date('m')-$num,0,date('Y'));
        return [$start_time,$end_time];
    }
}