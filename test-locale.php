<?php

use Illuminate\Support\Facades\App;

// Test if messages are in Portuguese
App::setLocale('pt_BR');

// Load validation messages
$messages = trans('validation');

echo 'Locale: '.App::getLocale()."\n";
echo 'Email message: '.$messages['email']."\n";
echo 'Required message: '.$messages['required']."\n";
echo 'Auth message: '.trans('auth.failed')."\n";
