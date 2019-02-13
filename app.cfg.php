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

    $cOptionsShort = '';
    $cOptionsLong = array(
        'hostnames:',
        'ips:',
        'htaccess:',
        'backup',
        'ipv6',
        'compat'
    );

    $cOptions = getopt( $cOptionsShort, $cOptionsLong );

    if( !isset( $cOptions['hostnames'] )
        || !isset( $cOptions['htaccess'] ) ) {
        echo 'Usage: dynamic.php --hostnames <file> --htaccess <file> [--ipv6] [--compat]' . "\n";
        exit( -1 );
    }

    if( !file_exists( $cOptions['hostnames'] ) ) {
        echo 'Error: The hostnames file does not exist' . "\n";
        exit( -1 );
    }


    define( 'BACKUP', isset( $cOptions['backup'] ) ? true : false );
    define( 'COMPAT', isset( $cOptions['compat'] ) ? true : false );
    define( 'IPV6', isset( $cOptions['ipv6'] ) ? true : false );

    define( 'FILE_HOSTNAMES', $cOptions['hostnames'] );
//    define( 'FILE_IPS', $cOptions['ips'] );
    define( 'FILE_HTACCESS', $cOptions['htaccess'] );

    define( 'OUTPUT_PREFIX', COMPAT ? "Order Deny,Allow\nDeny from all" : '<RequireAny>' );
    define( 'OUTPUT_SUFFIX', COMPAT ? '' : '</RequireAny>' );
    define( 'OUTPUT_ENTRY_PREFIX', COMPAT ? 'Allow from' : 'Require ip' );