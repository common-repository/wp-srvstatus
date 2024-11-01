<?php


/**
 * @author Martin S. Fredrich
 * @copyright 2010
 *
 * The terrifying uninstallation script.
 */
 
  if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
  {
      exit();
  }
  else
  {
    //Remove the plugin's settings
    delete_option( 'wgtitle' );
    delete_option( 'srvLink' );
    delete_option( 'serveraddress' );
    delete_option( 'port' );
    delete_option( 'dspname' );
    delete_option( 'srvLink1' );
    delete_option( 'serveraddress1' );
    delete_option( 'port1' );
    delete_option( 'dspname1' );
    delete_option( 'dspstyle' );
  }
?>