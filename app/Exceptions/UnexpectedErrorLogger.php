<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UnexpectedErrorLogger {

    /**
     * Logs the exception to the exceptions file for better readability in case of errors
     *
     * @return void
     */
    public static function log($err) {
        Log::channel('exception')->error($err);
    }
}
