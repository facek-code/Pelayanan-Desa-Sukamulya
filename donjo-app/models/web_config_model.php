<?php class Web_Config_Model extends CI_Model{

  function __construct(){
    parent::__construct();
  }

  function upload_favicon(){
    $icon = base_url() . LOKASI_LOGO_DESA . "favicon.png";
    $this->buat_favicon($icon);
  }

  function buat_favicon($icon) {
    require( dirname( __FILE__ ) . '/vendor/class-php-ico.php' );

    $destination = base_url() . LOKASI_LOGO_DESA . '/favicon.ico';

    $sizes = array(
        array( 16, 16 ),
        array( 24, 24 ),
        array( 32, 32 ),
        array( 48, 48 ),
    );

    $ico_lib = new PHP_ICO( $icon, $sizes );
    $ico_lib->save_ico( $destination );
  }
}
?>
