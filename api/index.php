<?php

/*
 * Pintu masuk aplikasi di Vercel.
 * Filesystem Vercel hanya bisa ditulis di /tmp, jadi semua berkas cache
 * Laravel diarahkan ke sana sebelum aplikasi dimuat.
 */
$tmp = '/tmp';

foreach (
    [
        'APP_CONFIG_CACHE'   => $tmp . '/config.php',
        'APP_EVENTS_CACHE'   => $tmp . '/events.php',
        'APP_PACKAGES_CACHE' => $tmp . '/packages.php',
        'APP_ROUTES_CACHE'   => $tmp . '/routes.php',
        'APP_SERVICES_CACHE' => $tmp . '/services.php',
        'VIEW_COMPILED_PATH' => $tmp . '/views',
    ] as $kunci => $nilai
) {
    putenv("$kunci=$nilai");
    $_ENV[$kunci] = $nilai;
    $_SERVER[$kunci] = $nilai;
}

if (! is_dir($tmp . '/views')) {
    mkdir($tmp . '/views', 0777, true);
}

// Teruskan permintaan ke index.php Laravel yang biasa
require __DIR__ . '/../public/index.php';

