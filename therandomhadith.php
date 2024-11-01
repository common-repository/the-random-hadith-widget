<?php
/*
Plugin Name: The Random Hadith Widget
Plugin URI: http://iknowledge.islamicnature.com/extras.php
Description: A widget that displays a random hadith
Author: Umar Sheikh
Author URI: http://www.indezinez.com
Version: 1.4
*/

add_action('widgets_init', 'load_random_hadith');

function load_random_hadith(){
  if(function_exists('register_widget')){
    register_widget('Random_Hadith');
	}
}

class Random_Hadith extends WP_Widget {

	function Random_Hadith(){
	
		$widget_ops = array('classname' => 'randomhadith', 'description' => __('A widget that displays a random hadith', 'randomhadith'));
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'random-hadith');
		$this->WP_Widget('random-hadith', __('Random Hadith', 'randomhadith'), $widget_ops, $control_ops);
	
	}

	function widget($args, $instance){
	
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if($title){
			echo $before_title.$title.$after_title;
		}
		if(function_exists('file_get_contents')){
      if($file = @file_get_contents('http://iknowledge.islamicnature.com/rh_script.php')){
        $file = preg_replace('/document\.write\(\'(.*)\'\)\;/','$1',$file);
        echo '<p>'.str_replace("\'","'",$file).'</p>';
      }else{
        echo '
        <p>
        <script type="text/javascript" src="http://iknowledge.islamicnature.com/rh_script.php"></script>
        <noscript>Please enable javascript. <a href="http://iknowledge.islamicnature.com">iKnowledge</a></noscript>
        </p>
        ';
      }
    }else{
      echo '
      <p>
      <script type="text/javascript" src="http://iknowledge.islamicnature.com/rh_script.php"></script>
      <noscript>Please enable javascript. <a href="http://iknowledge.islamicnature.com">iKnowledge</a></noscript>
      </p>
      ';
    }
		echo $after_widget;
		
	}

	function update($new_instance, $old_instance){
	
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
		
	}

	function form($instance){

		$defaults = array('title' => __('Random Hadith', 'randomhadith'));
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

?>