<?php
define('DATA_DIR', __DIR__ . '/../data/');
define('UPLOADS_DIR', __DIR__ . '/../uploads/');

if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0777, true);
}
if (!file_exists(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0777, true);
}

$default_users = [
    [
        'username' => 'admin',
        'password_hash' => password_hash('GraniteKingdomIsTheOnlyPlaceRocksTalkThisPasswordIsGettingLongLolGraniteRools', PASSWORD_DEFAULT)
    ]
];

if (!file_exists(DATA_DIR . 'users.json')) {
    file_put_contents(DATA_DIR . 'users.json', json_encode($default_users));
}

if (!file_exists(DATA_DIR . 'posts.json')) {
    file_put_contents(DATA_DIR . 'posts.json', json_encode([]));
}
