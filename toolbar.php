<?php
/*
Plugin Name: Jin Radio Toolbar
Plugin URI: http://widgets.jinradio.com/toolbar/
Description: Este plugin crea una barra de reproducción, usado en todas las webs de Jin Radio.
Version: v1.0
Author: Peke Soft, Ltd.
Author URI: http://www.pekesoft.com/
License: GPLv3
*/

/*  Copyright 2013  Ansh Gupta 
	You need written confirmation by Anshit Gupta before using or modifying
	the code in any of your project.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (!defined('MYPLUGIN_THEME_DIR'))
    define('MYPLUGIN_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('MYPLUGIN_PLUGIN_NAME'))
    define('MYPLUGIN_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
	
if (!defined('MYPLUGIN_PLUGIN_DIR'))
    define('MYPLUGIN_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MYPLUGIN_PLUGIN_NAME);
	
if (!defined('MYPLUGIN_PLUGIN_URL'))
    define('MYPLUGIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . MYPLUGIN_PLUGIN_NAME);
	
// create custom plugin settings menu
add_action('admin_menu', 'wwffb_floatingbar_create_menu');
$wwffb_flb_settings = get_option('wwffb_flb_settings');

function wwffb_floatingbar_create_menu() {

	//create new top-level menu
	add_menu_page('Floating Bar', 'Floating Bar', 'administrator', __FILE__, 'wwffb_floatingbar_settings_page',plugins_url('/images/icon.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'wwffb_register_mysettings' );
	
	
}


function wwffb_register_mysettings() {
	//register our settings	
	register_setting( 'wwffb_flb_settings_group', 'wwffb_flb_settings' );
}

function wwffb_floatingbar_settings_page() {
?>
<div class="wrap">
<h2>Floating Bar</h2>

<form method="post" action="options.php">
    <?php settings_fields('wwffb_flb_settings_group'); ?>
    <?php global $wwffb_flb_settings; ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Method</th>
        <td>
        
        <?php
		
			$automatic = ($wwffb_flb_settings['mode'] =='automatic' || !$wwffb_flb_settings['mode']) ? 'checked' : ''; 
			$manual = ($wwffb_flb_settings['mode'] =='manual') ? 'checked' : '';
		
		?>
        
        <input class="mode" id = "automatic"  type="radio" name="wwffb_flb_settings[mode]" value="automatic" <?php echo $automatic; ?> > <label for="automatic">Automatic </label>
        <input class="mode" id = "manual" type="radio" name="wwffb_flb_settings[mode]" value="manual" <?php echo $manual; ?> > <label for="manual">Manual</label>
        
        </td>
        </tr>
        
        <tr valign="top" class="html-wrapper">
        <th scope="row">Enter HTML</th>
        <td>
        
        <textarea rows="4" cols="50"  name="wwffb_flb_settings[html]"><?php echo $wwffb_flb_settings['html']; ?></textarea> 
        </td>
        </tr>   
        
        <tr valign="top" class="cat-wrapper">
        <th scope="row">Enter Category ID</th>
        <td><input type="text" name="wwffb_flb_settings[cat]" value="<?php echo $wwffb_flb_settings['cat']; ?>" /></td>
        </tr>              
        
        <tr valign="top">
        	<td colspan="2"><strong>Social Links</strong></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Facebook URL</th>
        <td>
            <textarea rows="4" cols="50"  name="wwffb_flb_settings[facebook]"><?php echo $wwffb_flb_settings['facebook']; ?></textarea> 
            <br />( Example: https://www.facebook.com/webloggerz )
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Twitter Username</th>
        <td>
            <textarea rows="4" cols="50"  name="wwffb_flb_settings[twitter]"><?php echo $wwffb_flb_settings['twitter']; ?></textarea>
            <br /> ( Example: webloggerz )
        </td>
        </tr>        
        
        <tr valign="top">
        <th scope="row">Google+ Page URL</th>
        <td>
            <textarea rows="4" cols="50"  name="wwffb_flb_settings[google]"><?php echo $wwffb_flb_settings['google']; ?></textarea>
            <br /> ( Example: https://plus.google.com/100302322511288585262 )
        </td>
        </tr>
    </table>
    <input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
    <?php //submit_button(); ?>

</form>
</div>
<?php } 


function wwffb_floating_bar($args = array(), $content = null) {
	global $wwffb_flb_settings; 
	?>
        

    <div class="fixedbar" >

        <div id="MenuInicio" style="position: absolute; bottom: 60px; left: 5px; width: 500px; height: 500px; background-color: #ff0000; z-index: 1000000" >
            <div class="MenuInicio"><img class="MenuInicio" src="<?php echo MYPLUGIN_PLUGIN_URL . '/images/JinRadioToolbar.png' ?>"/></div>
        </div>

        <div class="floatingbox">
      <ul id="tips">
        <li style="float: left;">
	
    <?php
    if($wwffb_flb_settings['mode'] =='automatic'){ 
		$args = array(
		'post_type' => 'post',
		'cat'  => $wwffb_flb_settings['cat'],
		'posts_per_page' => 1,
		'orderby' => 'rand'				
		);
		

	
		$myPosts = new WP_Query();
		$myPosts->query($args);
		
		while ($myPosts->have_posts()) : $myPosts->the_post(); ?>
			<a href='<?php the_permalink() ?>' title='<?php the_title(); ?>'><?php the_title(); ?></a>
		<?php endwhile; 
		
		//Reset Query
		wp_reset_query();
	}
	else{
		echo htmlspecialchars_decode($wwffb_flb_settings['html']); 
	}
	 ?>
                        
                        <a  class="tooltip" style="position: absolute; left: 5px; top: 0px;" title="Página principal de Jin Radio" href="http://www.jinradio.com" ><img class="alignnone size-full wp-image-1191" alt="Jin54" src="http://coolmusic.jinradio.com/wp-content/uploads/sites/2/2013/12/Jin54.png" width="54" height="54" /></a>
<a  class="tooltip" style="position: absolute; left: 70px; top: 10px;"  title="Escucha Jin Radio Channel One [EN DESARROLLO-SIN EMISIÓN]" href="http://one.jinradio.com"><img class="alignnone size-full wp-image-1193 disabled" alt="ChannelOne40" src="http://coolmusic.jinradio.com/wp-content/uploads/sites/2/2013/12/ChannelOne40.png" width="40" height="40" /></a> <a  style="position: absolute; left: 120px; top: 10px;" title="Escucha el canal CoolMusic de Jin Radio" href="http://coolmusic.jinradio.com"><img class="alignnone size-full wp-image-1194" alt="CoolMusic40" src="http://coolmusic.jinradio.com/wp-content/uploads/sites/2/2013/12/CoolMusic40.png" width="40" height="40" /></a> <a   style="position: absolute; left: 170px; top: 10px;"  title="Escucha el canal de música alternativa [EN DESARROLLO-SIN EMISIÓN]" href="http://alternative.jinradio.com"><img class="alignnone size-full wp-image-1192 disabled" alt="Alternative  [EN DESARROLLO-SIN EMISIÓN]" src="http://coolmusic.jinradio.com/wp-content/uploads/sites/2/2013/12/Alternative40.png" width="40" height="40" /></a> <a  style="position: absolute; left: 220px; top: 10px;"  title="Escucha el canal de noticias Jin News [EN DESARROLLO-SIN EMISIÓN]" href="http://news.jinradio.com"><img class="alignnone size-full wp-image-1195 disabled" alt="Jin News [EN DESARROLLO-SIN EMISIÓN]" src="http://coolmusic.jinradio.com/wp-content/uploads/sites/2/2013/12/JinNews40.png" width="40" height="40" /></a>

<a  style="position: absolute; left: 270px; top: 10px;"  title="Canal Óxido: canal temático de Rock." href="http://oxido.jinradio.com"><img class="alignnone size-full wp-image-1195" alt="Oxido Channel" src="http://coolmusic.jinradio.com/wp-content/uploads/sites/2/2014/02/IconoReproN.png" width="45" height="40" /></a>

<div  style="position: absolute; left: 330px; top: 1px;" >
<iframe id="JinPlayer" name="JinPlayer" width="200" height="60" src="http://antena.jinradio.com/Oxido.mp3" scrolling="no" frameborder="0"></iframe>
</div>
<a  style="position: absolute; left: 570px; top: 6px;"  href="http://antena.jinradio.com/Oxido.mp3" target="_blank" > <img alt="Jin Radio Óxido Channel" src="http://oxido.jinradio.com/wp-content/uploads/sites/7/2014/02/OxidoBN.png" width="129" height="40" /></a>

<!-- Script para detectar iOS/Android y cargar el reproductor nativo en el iFrame. Coded By ChuxMan -->
<script languaje="javascript">
// android
var ua = navigator.userAgent.toLowerCase();
var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");

// ipad
// For use within normal web clients 
var isiPad = navigator.userAgent.match(/iPad/i) != null;

// For use within iPad developer UIWebView
// Thanks to Andrew Hedges!
var ua = navigator.userAgent;
var isiPad = /iPad/i.test(ua) || /iPhone OS 3_1_2/i.test(ua) || /iPhone OS 3_2_2/i.test(ua);
// iphone/ipod
if (isAndroid || isiPad || ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)))) {
   document.getElementById("JinPlayer").src = "http://antena.jinradio.com/Oxido.mp3";
}
</script>

    <script>
        $(document).ready(function() {
            $('.tooltip').tooltipster();
        });
    </script>

<div class="reloj" style="top:-10px;position:absolute;right:0px; height:50px;width:300px"></div>

<script type="text/javascript">
	var reloj = $('.reloj').FlipClock({
		clockFace: 'TwentyFourHourClock'
	});
</script>
    </li>
    
    
    <?php if(!empty($wwffb_flb_settings['google'])) :?>
    <li>
  
    

   
<!-- Place this tag in your head or just before your close body tag. -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<!-- Place this tag where you want the +1 button to render. -->
<div class="g-follow" data-annotation="bubble" data-height="20" data-href="<?php echo $wwffb_flb_settings['google']; ?>"></div>    

    </li> 
    <?php endif; ?>
    
    <?php if(!empty($wwffb_flb_settings['twitter'])) :?>
	<li>
    
    
        <a href="https://twitter.com/<?php echo $wwffb_flb_settings['twitter']; ?>" class="twitter-follow-button" data-show-count="false" data-lang="en" data-show-screen-name="false">Follow</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    
    
    </li><?php endif; ?>
    
	<?php if(!empty($wwffb_flb_settings['facebook'])) :?>
    <li>

    <div class="fb-like" data-href="<?php echo $wwffb_flb_settings['facebook']; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
    
    </li>
 	<?php endif; ?>

      </ul>
    </div>
 </div>
    <?php 
	if(!empty($wwffb_flb_settings['facebook'])) : ?>
	
	    <div id="fb-root"></div>
	<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    
    <?php
	endif;
}


function wwffb_admin_inline_js(){ ?>


<?php global $wwffb_flb_settings; ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.mode').on("change", function() {
		var val = $(this).val();
		if(val == "manual"){
			$('.html-wrapper').show();
			//$('.cat-wrapper input').val('');
			$('.cat-wrapper').hide();
		}
		else if(val == "automatic"){
			//$('.html-wrapper input').val('');
			$('.cat-wrapper').show();
			$('.html-wrapper').hide();	
		}
	});
	<?php 
	if($wwffb_flb_settings['mode'] !='manual'){ ?>
		$('.cat-wrapper').show();
		$('.html-wrapper').hide();
	<?php }
	else{ ?>
		$('.cat-wrapper').hide();
		$('.html-wrapper').show();
	<?php }?>
	
});
</script>
<?php }
add_action( 'admin_print_scripts', 'wwffb_admin_inline_js', 100 );
add_action( 'wp_head', 'wwffb_flb_style' );

function prefix_on_deactivate() {
       delete_option('wwffb_flb_settings');
}

register_deactivation_hook(__FILE__, 'prefix_on_deactivate');

function wwffb_flb_style() { 
//wp_enqueue_script( 'jquery');	
?>
	
<style type="text/css" media="screen">

</style>
	
<?php }
add_action( 'wp_footer', 'wwffb_floating_bar' );

add_action('wp_enqueue_scripts', 'myplugin_styles');

function myplugin_styles() {

    wp_register_style('myplugin-css', MYPLUGIN_PLUGIN_URL . '/css/jintoolbar.css');
    wp_enqueue_style('myplugin-css');
   
    wp_register_style('flipclock-css', MYPLUGIN_PLUGIN_URL . '/css/flipclock.css');
    wp_enqueue_style('flipclock-css');
    
    wp_register_style('tooltipster-css', MYPLUGIN_PLUGIN_URL . '/css/tooltipster.css');
    wp_enqueue_style('tooltipster-css');
    
    wp_deregister_script('jquery');
    wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '');
    wp_enqueue_script('jquery');

    
    //wp_enqueue_script( 'custom-script', MYPLUGIN_PLUGIN_URL . '/js/jquery-2.2.2.min.js');
    wp_enqueue_script( 'custom-script', MYPLUGIN_PLUGIN_URL . '/js/flipclock.min.js');
    wp_enqueue_script( 'custom-script', MYPLUGIN_PLUGIN_URL . '/js/jquery.jplayer.min.js');
    wp_enqueue_script( 'custom-script', MYPLUGIN_PLUGIN_URL . '/js/jquery.tooltipster.min.js');
}