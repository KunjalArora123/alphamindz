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

$smtp_host   = isset($_SERVER['SMTP_HOST']) ? $_SERVER['SMTP_HOST'] : (getenv('SMTP_HOST') ?: 'smtp.gmail.com');
$smtp_port   = isset($_SERVER['SMTP_PORT']) ? $_SERVER['SMTP_PORT'] : (getenv('SMTP_PORT') ?: 587);
$smtp_user   = isset($_SERVER['SMTP_USER']) ? $_SERVER['SMTP_USER'] : (getenv('SMTP_USER') ?: 'prismlogic510@gmail.com');
$smtp_pass   = isset($_SERVER['SMTP_PASS']) ? $_SERVER['SMTP_PASS'] : (getenv('SMTP_PASS') ?: 'owydggxuaoaoxxwg');
$smtp_crypto = isset($_SERVER['SMTP_CRYPTO']) ? $_SERVER['SMTP_CRYPTO'] : (getenv('SMTP_CRYPTO') ?: 'tls');

$config['protocol']     = 'smtp';
$config['smtp_host']    = $smtp_host;
$config['smtp_port']    = (int) $smtp_port;
$config['smtp_user']    = $smtp_user;
$config['smtp_pass']    = $smtp_pass;
$config['smtp_crypto']  = $smtp_crypto;

$config['mailtype']     = 'html';
$config['charset']      = 'utf-8';
$config['wordwrap']     = TRUE;
$config['newline']      = "\r\n";
$config['crlf']         = "\r\n";
$config['smtp_timeout'] = 15;
