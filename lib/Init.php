<?php

namespace lib;

class Init {

    public static function init() {
        if (!Connection::query('show tables like \'posts\'')->fetch_assoc()) {
            Connection::queryMulti(file_get_contents('db.sql'));
        }

        if (!Connection::query('select 1 from posts limit 1')->fetch_assoc()) {
            self::generateData();
        }
    }

    private static function generateData() {
        $rows = [];

        for ($i = 0; $i < 100; $i++) {
            $rows[] = [
                'create_date' => '2025-06-' . self::randZeroLead(1, 30) . ' ' . self::randZeroLead(0, 23) . ':' . self::randZeroLead(0, 59) . ':' . self::randZeroLead(0, 59),
                'title' => 'title' . ($i + 1),
                'content' => 'content' . ($i + 1),
                'hotness' => rand(0, 1001)
            ];
        }

        Connection::insertBatch('posts', array_keys($rows[0]), $rows);
    }
    
    private static function randZeroLead($n, $k) {
        $r = rand($n, $k);

        if (strlen($r) == 1) {
            $r = '0' . $r;
        }

        return $r;
    }
}
