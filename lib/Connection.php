<?php

namespace lib;

class Connection {

    private static $connection;

    public static function connect() {
        if (!self::$connection) {
            require 'conf.php';

            self::$connection = mysqli_connect(
                $conf['host'],
                $conf['username'], 
                $conf['password'],
                $conf['database']
            );
        }

        return self::$connection;
    }

    public static function query($query) {
        $result = mysqli_query(self::connect(), $query);

        return $result;
    }

    public static function queryMulti($query) {
        $result = mysqli_multi_query(self::connect(), $query);

        // get results to let other queries execute
        while(mysqli_next_result(self::connect())) {
            
        }

        return $result;
    }

    public static function insert($table, $row) {
        return self::insertBatch($table, array_keys($row), [$row]);
    }

    public static function insertBatch($table, $columns, $rows) {
        $data = [];

        foreach ($rows as $row) {
            foreach ($row as &$value) {
                if ($value && !is_numeric($value)) {
                    $value = '\'' . self::escape($value) . '\'';
                }

                if ($value === '') {
                    $value = 'null';
                }
            }

            $data[] = '(' . implode(', ', $row) . ')';
        }

        $dataString = implode(', ', $data);

        $columnsString = implode(', ', $columns);

        $query = "insert into `$table` ($columnsString) values $dataString";

        $values = [];

        foreach ($columns as $column) {
            $values[] = $column . ' = values(' . $column . ')';
        }

        $valuesString = implode(', ', $values);

        $query .= ' on duplicate key update ' . $valuesString;

        return self::query($query);
    }

    public static function escape($string) {
        return mysqli_real_escape_string(self::connect(), $string);
    }

    public static function insertId() {
        return mysqli_insert_id(self::connect());
    }
}
