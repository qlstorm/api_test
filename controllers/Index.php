<?php

namespace controllers;

use lib\API;

class Index {

    public static function index(int $id = 0) {
        if ($id) {
            $row = API::getPost($id);

            return json_encode($row);
        }

        $list = API::getList();

        return json_encode($list);
    }
}
