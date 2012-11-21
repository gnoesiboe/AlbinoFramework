<?php

error_reporting(E_ALL);

foreach ($_SERVER['argv'] as $key => $arg)
{
  if (strpos($arg, '--env=') !== false)
  {
    sscanf($arg, '--env=%s', $env);
    unset($_SERVER['argv'][$key]);
  }
}

if (!isset($env))
{
  if (php_sapi_name() == 'cli')
  {
    $env = 'cli';
  }
  else
  {
    $env = 'dev';
  }
}

define('APPLICATION_ENV', $env);

chdir(dirname(__FILE__));
require_once dirname(__FILE__) . '/App/Bootstrap.php';
$bootstrap = new \Albino\Bootstrap();
$bootstrap->getApplication()->run();
exit(0);

/*fwrite(STDOUT, 'Please enter your name' . PHP_EOL);

$name = fgets(STDIN);

fwrite(STDOUT, 'Hello ' . $name);
exit(0);*/

fwrite(STDERR, 'test');

/*fwrite(STDOUT, "Pick some colors (enter the letter and press return)\n");

// An array of choice to color
$colors = array ( 'a'=>'Red', 'b'=>'Green', 'c'=>'Blue', );
fwrite(STDOUT, "Enter 'q' to quit\n");

// Display the choices
foreach ( $colors as $choice => $color )
{
  fwrite(STDOUT, "\t$choice: $color\n");
}

// Loop until they enter 'q' for Quit
do
{
  // A character from STDIN, ignoring whitespace characters
  do
  {
    $selection = fgetc(STDIN);
  }
  while ( trim($selection) == '' );

  if ( array_key_exists($selection,$colors) )
  {
    fwrite(STDOUT, "You picked {$colors[$selection]}\n");
  }
}
while ( $selection != 'q' );
exit(0);*/
/*
error_reporting(E_ALL);

// A custom error handler
function CliErrorHandler($errno, $errstr, $errfile, $errline)
{
  fwrite(STDERR, "$errstr in $errfile on $errline\n");
}

// Tell PHP to use the error handler
set_error_handler('CliErrorHandler');

fwrite(STDOUT,"Opening file foobar.log\n");

// File does not exist - error is generated
if ( $fp = fopen('foobar.log','r') )
{
  // do something with the file here
  fclose($fp);
}

fwrite(STDOUT,"Job finished\n");
exit(0);*/