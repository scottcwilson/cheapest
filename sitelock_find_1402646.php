<?php
//error_reporting(E_ALL ^ E_NOTICE);
if ( !isset($_GET['tc']) ) $_GET['tc'] = '';
$ok_ips = array('184.154.178.90','108.178.14.202','184.154.12.146','184.154.149.90','173.12.142.121', '173.48.192.172', '65.60.13.226', '184.154.159.2', '74.217.91.6', '108.178.48.90', '173.236.38.186');
#if ( !in_array( $_SERVER['REMOTE_ADDR'], $ok_ips ) ){
#    echo $_SERVER['REMOTE_ADDR'] . " not allowed";
#    exit;
#}
$is_secure = 0;
if ( isset($_SERVER['HTTPS']) and $_SERVER["HTTPS"] == "on" ){
    $is_secure = 1;
}
$os = PHP_OS;
$start_time = time();
$timeout = 30;
$timed_out = 0;
$output = '';
if ( preg_match('/^WIN/i', PHP_OS ) ){
    php_find( $output );
} 
else {
//php_find( $output );
    $output = php_find2( '.' );
}
if ( $timed_out ){
    echo "timed_out:1\n";
}
echo "mcrypt loaded : " . extension_loaded('mcrypt') . "\ntc : " . $_GET['tc'] . "\n";
if ( $_GET['tc'] == '54653616986567998789798883961613299144968911611281978545' ){
    echo "encrypted:no\nenc meth:none\nsitelock_start_data:\n" . base64_encode( $output );
} elseif ( $is_secure ){
    echo "encrypted:no\nenc meth:https\nsitelock_start_data:\n" . base64_encode( $output );
} else {
    if (function_exists('mcrypt_encrypt')) {
        $key = '54653616986567998789798883961613299144968911611281978545';
        $iv = '31753214';
        $encrypted = mcrypt_encrypt('blowfish', $key, $output, MCRYPT_MODE_CBC,$iv);
        echo "encrypted:yes\nenc meth:blowfish\nsitelock_start_data:\n" . base64_encode( $encrypted );
        
    } else {
        echo "encrypted:no\nsitelock_start_data:\n" . base64_encode( $output );
    }
}
function php_find ( &$output, $path = '.', $level = 0 ) {
    global $start_time, $timeout, $timed_out;
    $ignore = array( '.','..' );
    $dh = @opendir( $path );
    
    while( false !== ( $file = readdir( $dh ) ) ){
    
        if ( time() - $start_time > $timeout ){
            $timed_out = 1;
            return;
        }
        if( !in_array( $file, $ignore ) ){
            if ( is_link( "$path/$file" ) || is_readable( "$path/$file" ) ){
                
            } else if( is_dir( "$path/$file" ) ){
                php_find( $output, "$path/$file", ($level+1) );
                if ( $timed_out == 0 ){
                    $output .= implode( '+-+', array( "$path/$file", 'DIR' ) ) . "\n";
                }
            } else {
                $stat = stat("$path/$file");
                $output = $output . implode( '+-+', array("$path/$file", $stat['size'], $stat['mode'], $stat['mtime'] ) ) . "\n";
            }
        }
    
    }
    
    closedir( $dh ); 
}
function php_find2($dir = '.') {
   $result = array();
   
    $i = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::LEAVES_ONLY, RecursiveIteratorIterator::CATCH_GET_CHILD);
   foreach ($i as $d) {
        if (preg_match("@\.(git|svn|bzr)|CVS|\.$@", $d->getpathname())) continue;
        if (!$d->isFile() && !$d->isDir()) continue;
        if (!$d->isReadable()) continue;
        if ($d->isLink()) continue;
        $result[] = str_replace("$dir/", "", $d->getPathname()) . "+-+" . $d->getSize() . "+-+" . substr(sprintf("%o", $d->getPerms()), -4) . "+-+" . $d->getMTime();
   }
   return join("\n", $result);
}
 
