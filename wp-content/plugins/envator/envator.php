<?php
/* 
Plugin Name: Envator
Plugin URI: http://www.erdemarslan.com/wordpress/01-08-2013/457-envator-wordpress-plugin.html
Description: This plugin display yours envato items in pretty widget 
Author: Erdem Arslan
Version: 1.0
Author URI: http://www.erdemarslan.com
*/

/*  Copyright 2013  Erdem ARSLAN  (email : erdemsaid [at] gmail [dot] com )

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

$version = '1.0';

Class Envator Extends WP_Widget {
	
	private $defaults = array(
		'envatosites' => array( 'All Envato Sites', 'ActiveDen', 'AudioJungle', 'ThemeForest', 'VideoHive', 'GraphicRiver', '3DOcean', 'CodeCanyon', 'PhotoDune' ),
		'effects' => array( 'Random', 'Fade In and Scale', 'Slide In (Right)', 'Slide In (Bottom)', 'Newspaper', 'Fall', 'Side Fall', 'Sticky Up', '3D Flip (Horizontal)', '3D Flip (Vertical)', '3D Sign', 'Super Scaled', 'Just Me', '3D Slit', '3D Rotate Bottom', '3D Rotate In Left', 'Blur', 'Let Me In', 'Make Way!', 'Slip From Top' ),
		'numItems' => 4
	);

	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct( 'envator', 'Envator', array( 'description' => __('This plugin display yours envato items in pretty widget.', 'envator') ) );
		add_action( 'init', array($this, 'init_languages') );
	}
	
	public function init_languages()
    {
        load_plugin_textdomain('envator', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    }
	
	public function widget( $args, $instance ) {
		
		$title = $instance['title'];
		
		extract( $args, EXTR_SKIP );
		
		
		echo $before_widget;
		
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		
		$obj = (object) $instance;
		
		echo $this->envato_get_items( $obj, $widget_id );
		
		echo $after_widget;
	}
	
	
	private function envato_get_items( $obj, $widget_id )
	{
		// Firsly include envato class
		$dir = plugin_dir_path( __FILE__ );
		require_once $dir . 'assets/php/envato.class.php';
		$envato = new Envato_marketplaces;
		$envato->set_cache_dir( $dir . 'assets/php/cache' );
		
		// Get defaults
		$defaults = $this->defaults;
				
		// set variables
		$site	= $obj->esites == 0 ? '' : strtolower( $defaults['envatosites'][$ob->esites] );
		$ref	= $obj->refusername == '' ? '' : '?ref=' . $obj->refusername;
		
		
		// Check username and do everything
		if ( $obj->username == '' )
		{
			echo __('Please define an username for this widget!', 'envator');
		} else {
			
			// Get envato items
			$items = $envato->new_files_from_user($obj->username, $site);
			
			// count items
			$count = count( $items );
			
			// look is count > 0 or not!
			if ( $count > 0 )
			{

				// sort items to z-a
				$sort_arr = array();
				foreach($items AS $uniqid => $row){
					foreach($row AS $key=>$value){
						$sort_arr[$key][$uniqid] = $value;
					}
				}
				array_multisort($sort_arr['id'], SORT_DESC, $items);
				
				// defination loop times
				$loop = $obj->numitem > $count ? $count : $obj->numitem;
				
				$rs = ''; // rs means return string
				$ls = ''; // ls means link string				
				
				// print items in for loop
				for( $i=0; $i < $loop; $i++ ) {
					
					$price = explode('.',$items[$i]->cost);
					$modal = $obj->boxmodel == 0 ? $this->_random_box() : $obj->boxmodel;
					
					$rs .= '
					<div class="md-modal md-effect-' . $modal . '" id="modal-' . $widget_id . '-' . $i . '">
						<div class="md-content">
							<img src="' . $items[$i]->live_preview_url . '" />
							<div>
								<h3 class="md-color-pink">' . $items[$i]->item . ' [' . $items[$i]->id . ']</h3>
								<ul>
									<li>
										<i class="icons-dollar md-color-blue"></i> ' . $price[0] . ' 
										<i class="icons-cloud-download md-color-blue"></i> ' . $items[$i]->sales . ' 
										<i class="icons-star-half-full md-color-blue"></i> ' . $items[$i]->rating . ' 
										<i class="icons-calendar md-color-blue"></i> ' . date('d.m.Y', strtotime($items[$i]->uploaded_on)) . ' 
										<i class="icons-user md-color-blue"></i> ' . $items[$i]->user . '<br>
										<i class="icons-tags md-color-blue"></i> ' . $items[$i]->tags . '
									</li>
								</ul>
								
							</div>
							<a href="' . $items[$i]->url . $ref . '" class="md-close" target="_blank"><button>' . sprintf (__( 'Buy only $%d', 'envator' ), $price[0] ) . '</button></a>
							<button class="md-close">' . __('Close me!', 'envator') . '</button>
						</div>
					</div>' . "\n\r";
					
					$ls .= '
					<div class="polaroid">
						<a style="cursor:pointer;" class="md-trigger" data-modal="modal-' . $widget_id . '-' . $i . '"><img src="' . $items[$i]->thumbnail . '" /></a>
					</div>' . "\n\r";
					
					
				} // end for...
				
				$rs .= '<div class="md-links">' . $ls . '</div>' . "\n\r";
				$rs .= '<div class="md-overlay"></div>' . "\n\r";	
			
				// return string
				echo $rs;
				
			} else {
				echo sprintf( __('We dont find anything for this username: %s', 'envator'), $obj->username );
				echo '<br>';
				echo __('Please change username and envato site from widget options.', 'envator');
			}
		}
	}
	
	
	private function _random_box()
	{
		return rand(1,19);
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

        $instance['title'] 			= strip_tags(stripslashes($new_instance['title']));
        $instance['username']		= strip_tags(stripslashes($new_instance['username']));
		$instance['refusername']	= strip_tags(stripslashes($new_instance['refusername']));
        $instance['boxmodel']		= strip_tags(stripslashes($new_instance['boxmodel']));
		$instance['esites']			= strip_tags(stripslashes($new_instance['esites']));
        $instance['numitem']		= strip_tags(stripslashes($new_instance['numitem']));		
		
        return $instance;
	}
	
	
	
	public function form( $instance ) {
		
		$title			= esc_attr( $instance['title'] );
		$username		= esc_attr( $instance['username'] );
		$refusername	= esc_attr( $instance['refusername'] );
		$boxmodel		= esc_attr( $instance['boxmodel'] );
		$esites			= esc_attr( $instance['esites'] );
		$numitem		= esc_attr( $instance['numitem'] );
		
		if ( $numitem == 0 OR $numitem == '' OR !is_numeric($numitem))
		{
			$numitem = $this->defaults['numItems'];
		}
		
		?>

        <p>
        	<label for ="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php echo __( 'Widget Title' , 'envator' ); ?></strong>  
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
            </label>
        </p>
        
        <p>
        	<label for ="<?php echo $this->get_field_id( 'username' ); ?>"><strong><?php echo __( 'Envato Username for Listing Item' , 'envator' ); ?></strong>  
            <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
            </label>
        </p>
        
        <p>
        	<label for ="<?php echo $this->get_field_id( 'refusername' ); ?>"><strong><?php echo __( 'Envato Username for Referrer' , 'envator' ); ?></strong>  
            <input class="widefat" id="<?php echo $this->get_field_id( 'refusername' ); ?>" name="<?php echo $this->get_field_name( 'refusername' ); ?>" type="text" value="<?php echo $refusername; ?>" />
            </label>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id( 'boxmodel' ); ?>"><strong><?php echo __( 'Select Box Model Effect' , 'envator' ); ?></strong>
            <select class="widefat" id="<?php echo $this->get_field_id( 'boxmodel' ); ?>" name="<?php echo $this->get_field_name( 'boxmodel' ); ?>">
            	<?php
					foreach ($this->defaults['effects'] as $key => $value) {
					$select = $boxmodel == $key ? ' selected="selected"' : '';
				?>
            	<option value="<?php echo $key; ?>"<?php echo $select; ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
            </label>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id( 'esites' ); ?>"><strong><?php echo __( 'Select Item Resource' , 'envator' ); ?></strong>
            <select class="widefat" id="<?php echo $this->get_field_id( 'esites' ); ?>" name="<?php echo $this->get_field_name( 'esites' ); ?>">
            	<?php
					foreach ($this->defaults['envatosites'] as $key1 => $value1) {
					$select1 = $esites == $key1 ? ' selected="selected"' : '';
				?>
            	<option value="<?php echo $key1; ?>"<?php echo $select1; ?>><?php echo $value1; ?></option>
                <?php } ?>
            </select>
            </label>
        </p>
        
        <p>
        	<label for ="<?php echo $this->get_field_id( 'numitem' ); ?>"><strong><?php echo __( 'Number of Showing Items' , 'envator' ); ?></strong>  
            <input class="widefat" id="<?php echo $this->get_field_id( 'numitem' ); ?>" name="<?php echo $this->get_field_name( 'numitem' ); ?>" type="text" value="<?php echo $numitem; ?>" />
            </label>
        </p>
        
        <?php
		
	}
	
} // End Widget Class



// Init CSS and JS files
function init_wp_envator_widget() {
	
	global $version;
	register_sidebar( array( 'name' => 'Envator', 'description' => __( 'This plugin display yours envato items in pretty widget.', 'envator' ) ) );	

    if (!is_admin()) {
        $url = plugins_url( 'envator' );
				
		wp_enqueue_style( 'era_envator', $url.'/assets/css/envator.css', array(), $version);
		wp_enqueue_style( 'era_icons', $url.'/assets/css/icons.css', array(), $version);
		
        wp_enqueue_script( 'jquery');
        wp_enqueue_script( 'era_envator', $url.'/assets/js/jquery.modalEffects.js', array( 'jquery' ), $version );
		wp_enqueue_script( 'era_envator1', $url.'/assets/js/envator.js', array( 'jquery' ), $version );
		wp_enqueue_script( 'era_envator2', $url.'/assets/js/modernizr.custom.js', array(), $version );
		wp_enqueue_script( 'era_envator3', $url.'/assets/js/classie.js', array(), $version );
		wp_enqueue_script( 'era_envator4', $url.'/assets/js/cssParser.js', array(), $version );
    }
}

// Register Widget
function register_wp_envator_widget() {
	register_widget( 'Envator' );
}

// Add actions
add_action( 'init', 'init_wp_envator_widget' );
add_action( 'widgets_init', 'register_wp_envator_widget' );

