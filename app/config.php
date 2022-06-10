<?php
// настройки для PDO
define('DB_DRIVER', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'third_level');
define('DB_USER', 'root');
define('DB_PASS', '');

// для аватарок
define('AVATARS', 'storage\avatars\\'); // для сохранения аватарок на сервер
define('AVATARS_HTML', '/storage/avatars/'); // для вывода аватарок в HTML коде 
define('AVATAR_NOT', '/storage/avatar_not/avatar.png'); // аватарка-заглушка
define('AVATAR_FAKER', 'img\demo\avatars\*.*'); // для генератора аватарок

// константы для компонента валидации
define('PASSWORD_MIN', 3);
define('PASSWORD_MAX', 15);
define('USERNAME_MIN', 3);
define('USERNAME_MAX', 15);
define('ABOUT_MAX', 500);
define('IMAGE_SIZE_MIN', null);
define('IMAGE_SIZE_MAX', '20MB');
