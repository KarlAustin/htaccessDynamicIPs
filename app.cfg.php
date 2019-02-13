<?php
/**
 * Copyright (c) Karl Austin - 2019, All Rights Reserved.
 */

/**
     * Created by PhpStorm.
     * User: karlaustin
     * Date: 13/02/2019
     * Time: 09:45
     */

    define( 'APP_DIR', __DIR__ );

    define( 'BACKUP_SUFFIX', '.bak' );
    define( 'BLOCK_START', '#* DYNAMIC IPS -- START *#' );
    define( 'BLOCK_END', '#* DYNAMIC IPS -- END *#' );

    $cOptionsShort = 'h';
    $cOptionsLong = array(
        'hostnames:',
        'ips:',
        'htaccess:',
        'backup',
        'ipv6',
        'compat',
        'litespeed',
        'help'
    );

    $cOptions = getopt( $cOptionsShort, $cOptionsLong );

    if( !isset( $cOptions['hostnames'] )
        || !isset( $cOptions['htaccess'] )
        || isset($cOptions['h'] )
        || isset($cOptions['help'] )) {

        echo <<<EOD

         USAGE:

         dynamic.php --htaccess <file> --hostnames <file> [--ipv6] [--backup] [--compat|--litespeed]

         -h|--help        This help message
         --htaccess       Path to the .htaccess file you'd like to put the IPs in
         --hostnames      Path to the file containing a list of dynamic hosts, one entry per line
         --ipv6           Optional: Perform IPv6 lookup on each hostname as well
         --backup         Optional: Make a backup (default prefix .bak) of the .htaccess file
         --compat         Optional: Apache 2.2 syntax
         --litespeed      Optional: Same as --compat, enabling Apache 2.2 syntax for Litespeed Web Server

        WARNING: Any new .htaccess file or backup file that is created, will be created as the user running this script.
                 Similarly, any new file created will be created with the system default umask.


EOD;
        exit( -1 );
    }

    if( !file_exists( $cOptions['hostnames'] ) ) {
        echo 'Error: The hostnames file does not exist' . "\n";
        exit( -1 );
    }


    define( 'BACKUP', isset( $cOptions['backup'] ) ? true : false );
    define( 'COMPAT', ( isset( $cOptions['compat'] ) || isset( $cOptions['litespeed'] ) ) ? true : false );
    define( 'IPV6', isset( $cOptions['ipv6'] ) ? true : false );

    define( 'FILE_HOSTNAMES', $cOptions['hostnames'] );
//    define( 'FILE_IPS', $cOptions['ips'] );
    define( 'FILE_HTACCESS', $cOptions['htaccess'] );

    define( 'OUTPUT_PREFIX', COMPAT ? "Order Deny,Allow\nDeny from all" : '<RequireAny>' );
    define( 'OUTPUT_SUFFIX', COMPAT ? '' : '</RequireAny>' );
    define( 'OUTPUT_ENTRY_PREFIX', COMPAT ? 'Allow from' : 'Require ip' );