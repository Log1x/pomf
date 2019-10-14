<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Token
    |--------------------------------------------------------------------------
    |
    | The token is used as verification before allowing an image upload.
    | It should be a private, randomly generated string.
    |
    */

    'token' => '',

    /*
    |--------------------------------------------------------------------------
    | Upload Directory
    |--------------------------------------------------------------------------
    |
    | The directory used for uploaded images. It must have an appended '/'.
    | If you want to use the current directory, simply set it to false.
    |
    */

    'dir' => 'screenshots/',

    /*
    |--------------------------------------------------------------------------
    | Maximum Filesize
    |--------------------------------------------------------------------------
    |
    | The maximum filesize of an uploaded image in megabytes.
    |
    */

    'size' => 100,

    /*
    |--------------------------------------------------------------------------
    | Filename Slug
    |--------------------------------------------------------------------------
    |
    | The filename slug is a string appended to the uploaded image filename.
    | If you do not want a filename slug, simply set it to false.
    |
    */

    'slug' => 'Screenshot_',

    /*
    |--------------------------------------------------------------------------
    | Filename Timestamp
    |--------------------------------------------------------------------------
    |
    | The timestamp is used for generating a randomized image filename.
    | It is based off of PHP's built in date() function. If set to false, your
    | filename will be randomly generated using the Hashids library instead
    | which will give you unique filenames similar to Imgur.
    |
    */

    'timestamp' => 'Y-m-d_H-i-s',

];
