<?php

    function stringEncode(string $input) : string {
        return base64_encode($input);
    }

    function stringDecode(string $input) : string {
        return base64_decode($input);
    }

    // ------------------------------ ↓ Numbers ↓ ------------------------------ //

    function sqlcmd_createNumberTable() : string {
        return "CREATE TABLE number (
                    id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    name VARCHAR(20) NOT NULL UNIQUE,
                    value INT(6) UNSIGNED NOT NULL DEFAULT 0
                )";
    }

    function sqlcmd_addNumber(string $name) : string {
        return "INSERT INTO number (name) VALUES ('$name')";
    }

    function sqlcmd_getNumber(string $name) : string {
        return "SELECT value FROM number WHERE name = '$name'";
    }

    function sqlcmd_addNumberByOne(string $name) : string {
        return "UPDATE number SET value = value + 1 
                WHERE name = '$name'";
    }

    // ------------------------------ ↓ Users ↓ ------------------------------ //

    function sqlcmd_createUserTable() : string {
        return "CREATE TABLE user (
                    id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    username VARCHAR(40) NOT NULL UNIQUE,
                    password VARCHAR(128) NOT NULL,
                    reg_date TIMESTAMP, -- NOT NULL,
                    avatar VARCHAR(40) NOT NULL DEFAULT 'https://i.imgur.com/9B9e2OY.png',
                    login_turn INT(8) UNSIGNED NOT NULL DEFAULT 0
                )";
    }

    function sqlcmd_checkUserExist(string $user_id) : string {

        return "SELECT user_id FROM user 
                WHERE user_id = '$user_id'";
    }
    
    function sqlcmd_addUser(string $user_id, string $password, string $nickname) : string {

        $sha512_pwd = hash('sha512', $password);
        $encode_nickname = stringEncode($nickname);

        return "INSERT INTO user (user_id, password, nickname) 
                VALUES ('$user_id', '$sha512_pwd', '$encode_nickname')";
    }
    
    function sqlcmd_getUser(string $username, string $password) : string {

        $encode_username = stringEncode($username);
        $sha512_pwd = hash('sha512', $password);

        return "SELECT username FROM user 
                WHERE username = '$encode_username' && password = '$sha512_pwd'";
    }

    function sqlcmd_addUserLoginTurn(string $username) : string {

        $encode_username = stringEncode($username);

        return "UPDATE user SET login_turn = login_turn + 1 
                WHERE username = '$encode_username'";
    }

    function sqlcmd_getUserLoginTurn(string $username) : string {

        $encode_username = stringEncode($username);

        return "SELECT login_turn FROM user WHERE username = '$encode_username'";
    }
    
    function sqlcmd_getAvatar(string $username) {

        $encode_username = stringEncode($username);

        return "SELECT avatar FROM user 
                WHERE username = '$encode_username'";
    }
    
    function sqlcmd_updateAvatar(string $username, string $link) : string {

        $encode_username = stringEncode($username);

        return "UPDATE user SET avatar = '$link' 
                WHERE username = '$encode_username'";
    }

    // ------------------------------ ↓ Comments ↓ ------------------------------ //
    
    function sqlcmd_createCommentTable() : string {
        return "CREATE TABLE comment (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    username VARCHAR(40) NOT NULL,
                    date TIMESTAMP, -- NOT NULL,
                    alive BOOLEAN NOT NULL DEFAULT TRUE,
                    title VARCHAR(40) NOT NULL,
                    content TEXT NOT NULL
                )";
    }

    function sqlcmd_getComments(int $page) : string {

        $last_item = $page * 10 + 1;
        $first_item = $last_item - 10;

        return "SELECT comment.*, user.avatar FROM comment, user 
                WHERE comment.id >= $first_item AND comment.id <= $last_item AND comment.username=user.username";
    }

    function sqlcmd_getCommentById(int $id) : string {
        return "SELECT username FROM comment WHERE comment.id = $id";
    }

    function sqlcmd_deleteComment(int $id) : string {
        return "UPDATE comment SET alive = false 
                WHERE id = $id";
    }

    function sqlcmd_addComment(string $username, string $title, string $content) : string {

        $encode_username = stringEncode($username);
        $encode_title = stringEncode($title);
        $encode_content = stringEncode($content);

        return "INSERT INTO comment (username, title, content) 
                VALUES ('$encode_username', '$encode_title', '$encode_content')";
    }
    
?>