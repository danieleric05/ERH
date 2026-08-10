<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

/*
|--------------------------------------------------------------------------
| Serve Existing Static Files
|--------------------------------------------------------------------------
|
| The public directory of this application is the parent directory
| of the Laravel application directory.
|
| Structure:
|
| /erh
| ├── index.php
| ├── css/
| ├── js/
| └── site/
|
*/

$publicPath = realpath(__DIR__ . '/..');

if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

/*
|--------------------------------------------------------------------------
| Forward Request To Laravel
|--------------------------------------------------------------------------
*/

require_once $publicPath . '/index.php';
