<?php
namespace eacoo;
use think\Db;
/**
 * Sql语句处理执行类
 */
class Sql {

    /**
     * 执行文件中SQL语句函数
     * @param string $file sql语句文件路径
     * @param string $s_tablepre  自己的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public static function executeSqlByFile($file, $s_tablepre='') {
        $sql_data = file_get_contents($file);
        if (!$sql_data) {
            return false;
        }
        $sql_format = self::sqlSplit($sql_data, $s_tablepre);
        $counts = count($sql_format);
        for ($i = 0; $i < $counts; $i++) {
            $sql = trim($sql_format[$i]);
            try {
                Db::execute($sql);
            } catch (\Exception $e) {
                throw new \think\Exception($e);
            }
            
        }
        return true;
    }

    /**
     * 解析数据库语句函数
     * @param string $sql  sql语句   带默认前缀的
     * @param string $s_tablepre  自己的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public static function sqlSplit($sql, $s_tablepre='') {

        $r_tablepre = config('database.prefix');
        if ($r_tablepre != $s_tablepre) {
            $sql          = str_replace($s_tablepre, $r_tablepre, $sql);
        }

        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);

        $sql          = str_replace("\r", "\n", $sql);
        $ret          = array();
        $num          = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries   = explode("\n", trim($query));
            $queries   = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-') {
                    $ret[$num] .= $query;
                }

            }
            $num++;
        }
        
        return $ret;
    }

    
}
