<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Configuration
|--------------------------------------------------------------------------
|
| Configuration settings for CodeIgniter's Email class.
|
*/

$config['protocol']     = 'smtp';
$config['smtp_host']    = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
$config['smtp_port']    = getenv('SMTP_PORT') ?: 587;
$config['smtp_user']    = getenv('SMTP_USER') ?: 'info.alphamindz@gmail.com';
$config['smtp_pass']    = getenv('SMTP_PASS') ?: 'dufzsgwwedslnfgb';
$config['smtp_crypto']  = getenv('SMTP_CRYPTO') ?: 'tls';

$config['mailtype']     = 'html';
$config['charset']      = 'utf-8';
$config['wordwrap']     = TRUE;
$config['newline']      = "\r\n";
$config['crlf']         = "\r\n";
$config['smtp_timeout'] = 15;
