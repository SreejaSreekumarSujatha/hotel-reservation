<?php
//  Set up error logging immediately
error_reporting(E_ALL); // log all types of errors
ini_set('log_errors', 1);
ini_set('display_errors', 0); // hide errors from users in production

// Ensure logs folder exists
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
ini_set('error_log', __DIR__ . '/../logs/app-error.log');
// Test logging
//error_log("Test message: logging works");

//this line load all php libraraies that were installed using composer
 require_once __DIR__ .'/../vendor/autoload.php';

 $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();
try{
    $dsn="mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";
    $pdo=new PDO($dsn,$_ENV['DB_USER'],$_ENV['DB_PASS'],
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES=>false,]);

}
catch(PDOException $e)
{
    //log the error

error_log($e->getMessage());
echo"Something went wrong.Please try again later";
}