<?php
/**
 * Copyright (c) Karl Austin - 2019, All Rights Reserved.
 */

/**
     * Created by PhpStorm.
     * User: karlaustin
     * Date: 13/02/2019
     * Time: 09:48
     */

    /**
     * This makes heavy use of arrays, it could use contactenated strings in places, but that would make inserting any
     * future processing steps (who knows) more difficult.  The cost of arrays will only be an issue with 1000s of
     * entries - in which case, using .htaccess isn't the answer (get a VPN!)
     */

    include_once( 'app.cfg.php' );

    $lHostnames = file( FILE_HOSTNAMES, FILE_SKIP_EMPTY_LINES );
//    $lIPs = file( FILE_IPS, FILE_SKIP_EMPTY_LINES );
    if( !file_exists( FILE_HTACCESS ) ) {
        $lHtaccess = '';
    } else {
        $lHtaccess = file_get_contents( FILE_HTACCESS );

        $lBlockStartPosition = strpos( $lHtaccess, BLOCK_START );
        $lBlockEndPosition = strpos( $lHtaccess, BLOCK_END );

        if ($lBlockStartPosition === false && $lBlockEndPosition !== false) {
            /**
             * No block start present, but block end is present.
             * We don't know where we start, we can't alter .htaccess reliably
             */
            exit( -1 );
        } elseif ($lBlockStartPosition !== false && $lBlockEndPosition === false) {
            /**
             * No block end present, but block start is present.
             * We don't know where we end, we can't alter .htaccess reliably
             * We'd be guessing that an allow line was put there by us if we checked for that.
             */
            exit( -1 );
        }
    }

    /**
     * Fetch DNS A and AAAA (if ipv6 is set) records for each hostname
     *
     * In theory dns_get_record could return multiple A or AAAA records per hostname, in practice it probably won't.
     *
     * Keeping v4 and v6 arrays separate in case we want to do anything different down the line
     */
    $lRecords4 = array();
    $lRecords6 = array();
    foreach( $lHostnames as $lHostname ) {
        $lHostname = trim( $lHostname );
        if( ($tRecords4 = dns_get_record( $lHostname, DNS_A )) !== false ) {
            $lRecords4 = array_merge( $lRecords4, $tRecords4 );
        }
        if( IPV6 ) {
            if( ($tRecords6 = dns_get_record( $lHostname, DNS_AAAA )) !== false ) {
                $lRecords6 = array_merge( $lRecords6, $tRecords6 );
            }
        }
    }

    $lIPList = array_merge( $lRecords4, $lRecords6 );
    $lEntry = '';
    $lEntries = array();
    foreach( $lIPList as $lHost ) {
        $lIP = isset( $lHost['ipv6'] ) ? $lHost['ipv6'] : $lHost['ip'];
        $lEntries[] = OUTPUT_ENTRY_PREFIX . ' ' . $lIP . ' #- ' . $lHost['host'];
    }

    $lOutput = BLOCK_START . "\n";
    $lOutput .= OUTPUT_PREFIX . "\n";
    $lOutput .= join( "\n", $lEntries );
    $lOutput .= "\n" . OUTPUT_SUFFIX . "\n";
    $lOutput .= BLOCK_END;

    if( $lHtaccess == '' ) {
        /**
         * No existing .htaccess file.
         */
        file_put_contents( FILE_HTACCESS, $lOutput );
    } else {
        /**
         * Existing .htaccess. Let's. Get. Mangling.
         */
        if( $lBlockStartPosition !== false ) {
            $tStart = $lBlockStartPosition;
            $tEnd = $lBlockEndPosition + strlen( BLOCK_END );
            $lNewHtaccess = substr( $lHtaccess, 0, $tStart );
            $lNewHtaccess .= "\n" . $lOutput;
            $lNewHtaccess .= "\n" . substr( $lHtaccess, $tEnd );
        } else {
            $lNewHtaccess = $lHtaccess . "\n" . $lOutput;
        }
    }

    /**
     * User wants us to make a backup of the current .htaccess
     */
    if( BACKUP ) {
        file_put_contents( FILE_HTACCESS . BACKUP_SUFFIX, $lHtaccess );
    }

    file_put_contents( FILE_HTACCESS,  $lNewHtaccess );