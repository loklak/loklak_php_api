<?php 
add_action( 'admin_init' , 'register_fields'  );


function register_fields() {

    register_setting( 'loklak-settings', 'loklak-settings' );

    add_settings_section(
        'loklak_section', 
        '1. Loklak API', 
        'loklak_section_callback', 
        'loklak-settings'
    );

    add_settings_field( 
        'loklak_api', 
        null, 
        'loklak_api_html_render', 
        'loklak-settings', 
        'loklak_section',
        array('class' => 'loklak_settings')
    );
}

function loklak_api_html_render(  ) { 

    $option = get_option( 'loklak-settings[loklak_api]' );
    
    echo '
    <p>
        <input type="checkbox" name="loklak-settings[loklak_api]" value="loklak"';
        
            if(!empty($option) && esc_attr($option) == true){
                var_dump("1");
                print ' checked="checked"';
            }
        
    echo '>Use anonymous API of <a href="http://loklak.org/">loklak.org</a> and get plugin data through loklak (no registration and authentication required). <a href="http://loklak.org/">Find out more</a><br/>
    </p><br/>';
}

function loklak_settings_conf(  ) {

    if( isset($_POST['loklak-settings']) ){
        update_option('loklak-settings[loklak_api]', true);
    }
    else {
        update_option('loklak-settings[loklak_api]', false);
    }
}

function loklak_section_callback(  ) { 

}

function loklak_settings_custom_script() {
    
}

function loklak_settings_custom_style() {
    wp_register_style( 'loklak-settings-css', plugin_dir_url( __FILE__ ).'../Assets/css/loklak-api-admin.css');
    wp_enqueue_style( 'loklak-settings-css' );
}

add_action( 'admin_enqueue_scripts', 'loklak_settings_custom_style' );