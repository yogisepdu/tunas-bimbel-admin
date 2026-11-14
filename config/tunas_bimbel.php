<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Informasi Admin Tunas Bimbel
    |--------------------------------------------------------------------------
    */

    'admin_name' => env(
        'TUNAS_ADMIN_NAME',
        'Admin Tunas Bimbel'
    ),

    'admin_email' => env(
        'TUNAS_ADMIN_EMAIL',
        'tunasbimbel29@gmail.com'
    ),

    /*
    | Nomor WhatsApp harus menggunakan format internasional
    | tanpa +, spasi, atau tanda -
    |
    | Contoh:
    | 6285709569709
    */

    'admin_whatsapp' => env(
        'TUNAS_ADMIN_WHATSAPP',
        '6285709569709'
    ),

];
