<?php

namespace lib;

class API {

    public static function getList() {
        $userId = (int)User::getId();

        $result = Connection::query('
            SELECT * FROM posts 
            WHERE
                hotness < 1001 and
                posts.id not in (
                    SELECT post_id FROM users_views
                    WHERE
                        user_id = ' . (int)$userId . '
                )
            order by hotness desc
            limit 10
        ');

        $rows = $result->fetch_all();

        return $rows;
    }

    public static function getPost(int $postId) {
        self::processPost($postId);

        $result = Connection::query('
            SELECT * FROM posts
            WHERE
                id = ' . $postId
        );

        $row = $result->fetch_assoc();

        return $row;
    }

    public static function processPost(int $postId) {
        $userId = (int)User::getId();

        $usersViewsResult = Connection::query('
            SELECT 1 FROM users_views
            WHERE
                user_id = ' . $userId . ' and
                post_id = ' . $postId . '
        ');

        $query = '
            START TRANSACTION;
            
            UPDATE posts
            SET
                hotness = hotness + 1
            WHERE
                id = ' . $postId . ';
        ';

        if (!$usersViewsResult->fetch_assoc()) {
            $query .= "

                INSERT INTO users_views (user_id, post_id) VALUES ($userId, $postId);
            ";
        }

        $query .= '

            COMMIT;
        ';

        Connection::queryMulti($query);
    }
}
