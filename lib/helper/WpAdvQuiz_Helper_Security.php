<?php

class WpAdvQuiz_Helper_Security
{
	public function init()
    {	
		if( is_admin() )
		{
          add_action( 'admin_init', array( $this, 'force_nonce_init' ),1 );
		  add_action( 'admin_footer', array( $this, 'force_nonce_foot' ),999 );
		}
    }
	
	public function force_nonce_init()
	{
		
		$page = filter_input(INPUT_GET,'page',FILTER_SANITIZE_STRING);
		$page = $page ? : null;
		$plugin_page = !empty( $page ) ? plugin_basename( stripslashes( $page ) ) : null;

		$wpnonce = filter_input(INPUT_GET,'_wpnonce',FILTER_SANITIZE_STRING);
		$wpnonce = $wpnonce ? : null;
		
		if( !empty( $plugin_page ) ):
			if(	!wp_verify_nonce( $wpnonce, 'wpnonce' )  )
				if( defined( 'DOING_AJAX' ) && DOING_AJAX ):
					die( -1 );
				endif;
		endif;

		ob_start();
	}

	public function force_nonce_foot()
	{
		
			$page = filter_input(INPUT_GET,'page',FILTER_SANITIZE_STRING);
			$page = $page ? : null;
			$plugin_page = !empty( $page ) ? plugin_basename( stripslashes( $page ) ) : '';
		
			$nonce = wp_create_nonce( 'wpnonce' );
			$content = ob_get_contents();
			$baw_force_token = wp_generate_password( 10, false );
			$new_fields = wp_nonce_field( 'wpnonce' . date( 'a' ), '_wpnonce', false, false ) . '<input type="hidden" name="page" value="' . $plugin_page . '" />';
			if( !empty( $plugin_page ) )
				$content = preg_replace( '/(<form(.+)>)/', '$1' . $new_fields, $content );
			$content = preg_replace( '/(\&|\?)page=/i', '$1_wpnonce=' . $nonce . '&page=', $content );
			ob_end_clean();
			echo filter_var($content,FILTER_UNSAFE_RAW);
			if( !empty( $plugin_page ) ):
				?>
				<script>
				jQuery( 'body' ).on( 'ajaxSend' , function( elm, xhr, s ){
					if( s.data!=null && s.data.indexOf( '_wpnonce=' ) === -1 ){
						if( s.data.indexOf( '&page=' ) === -1 && s.data.indexOf( '?page=' ) === -1 )
							s.data = s.data + '&page=<?php echo $plugin_page; ?>&_wpnonce=<?php echo $nonce ?>';
					}
				});
				</script>
				<?php
			endif;
	}

}

?>