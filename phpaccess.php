<?php
//PHP Built-in server simillar to Apache .htacces(/w mod_rewrite). For development 'envy' only.

$fileDir = \array_reverse(\explode('\\', __DIR__));

if($fileDir[0] === 'bin'){
    $appRoot = \realpath(__DIR__ . '../../');
} else {
    $appRoot = \realpath(__DIR__ . '../../../../');
}

if (\preg_match('/\.(?:png|jpg|jpeg|gif|txt)$/', \getenv('REQUEST_URI'))) {
    return false;
} else {
    $exp = \explode('/', \getenv('REQUEST_URI'));
    if(isset($exp[1])){
        $app    = $exp[1];
        $q      = \preg_replace('|\/'. $exp[1] .'\/|', '', \getenv('REQUEST_URI'));
        $_GET['q'] = $q;
        include $appRoot . \DIRECTORY_SEPARATOR .'index.php';
    } else {
        return false;
    }
}
