<?php

$help = "type manage.php install|create <controller_name>";

$paths = [
    'home'      => \realpath(__DIR__ . '/'),
    'src'       => __DIR__ . \DIRECTORY_SEPARATOR .'src'. \DIRECTORY_SEPARATOR,
    'template'  => __DIR__ . \DIRECTORY_SEPARATOR. 'src'. \DIRECTORY_SEPARATOR .'Template'. \DIRECTORY_SEPARATOR .'views' .\DIRECTORY_SEPARATOR
];

$dirs = [
    $paths['src'],
    $paths['template']
];

if (\count($argv) > 1) {
    switch ($argv[1]) {
        case 'create':
            run($paths, $dirs);
            break;
        case 'install':
            build($paths, $dirs);
            break;
        default:
            exit($help);
            break;
    }
} else {
    exit($help);
}

function build(array $paths, array $dirs) 
{
    $home       = $paths['home'];

    print "> verifying dirs ...\n";
    foreach ($dirs as $dir) {
        if (\is_dir($dir) === false) {
            \exec("mkdir ". $dir);
        }
    }

    \chdir($home);

    if (\is_dir($home . 'vendor') === false) {
        print "> composer install ...\n";
        \exec("composer install", $stdout);
        print outputExec($stdout);
    }
    print("\n App succesfully built! have run! :)\n");
    exit(0);
}

function isBuilt(array $paths, array $dirs) 
{
    $home       = $paths['home'];

    $directories = true;

    foreach ($dirs as $dir) {
        if (\is_dir($dir) === false) {
            $directories = false;
            break;
        }
    }

    if (\is_dir($home . 'vendor') === true and $directories === true) {
        return true;
    } else {
        return false;
    }
}

function outputExec(array $stdout) 
{
    $output = "";
    foreach ($stdout as $line) {
        $output .= $line . "\n";
    }
    return $output;
}
