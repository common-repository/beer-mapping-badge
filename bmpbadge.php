<?php
/*
Plugin Name: Beer Mapping Badge Widget
Description: Shows your beermapping badge and street cred
Author: beerinator
Version: 1.3
Author URI: http://beermapping.com
*/

function widget_bmpbadge_init()
{	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;	

	// main widget function
	function widget_bmpbadge($args) {
		require_once(ABSPATH . WPINC . '/rss-functions.php');
		
		// get my options
		extract($args);
		$options = get_option('widget_bmpbadge');
		$title = $options['title'];
		$user = $options['user'];
		$showbadge = $options['showbadge']; 
		$showscores = $options['showscores'];

		// start the widget		
		echo $before_widget;
		$title ? print($before_title . $title . $after_title) : null;
		echo '<div class="bmpbadgewidget">';

		// print the beer list	
		if($showbadge){
			$feed = "http://beermapping.com/beermappers/badge.php?u=".$user;
			$feedContent = @fetch_rss($feed);
			$feedItems = $feedContent->items;
			echo "";
			foreach ($feedItems as $key => $row) {
				echo $row['description'];
			}
			echo "";
		}
		// print the high scores list	
		if($showscores){
			$feed = "http://beermapping.com/beermappers/userscores.php?u=".$user;
			$feedContent = @fetch_rss($feed);
			$feedItems = $feedContent->items;
			echo "";
			foreach ($feedItems as $key => $row) {
				echo $row['description'];
			}
			echo "";
		}
		
		echo "</div>";
		echo $after_widget;
	}

	// control panel
	function widget_bmpbadge_control() {
		$options = $newoptions = get_option('widget_bmpbadge');
		if ( $_POST["bmpbadge-submit"] ) {
			$newoptions['title'] = trim(strip_tags(stripslashes($_POST["bmpbadge-title"])));
			$newoptions['user'] = trim(strip_tags(stripslashes($_POST["bmpbadge-user"])));
			$newoptions['showbadge'] = isset($_POST["bmpbadge-showbadge"]);
			$newoptions['showscores'] = isset($_POST["bmpbadge-showscores"]);
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_bmpbadge', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$user = htmlspecialchars($options['user'], ENT_QUOTES);	
		$showbadge = $options['showbadge'] ? 'checked="checked"' : '';
		$showscores = $options['showscores'] ? 'checked="checked"' : '';	
		
	?>
		<p><label for="bmpbadge-title">Give the widget a title (optional):</label>
		<input style="width: 100%;" id="bmpbadge-title" name="bmpbadge-title" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="bmpbadge-user">Enter the user name of the beermapping user:</p>
		<input style="width: 100%;" id="bmpbadge-user" name="bmpbadge-user" type="text" value="<?php echo $user; ?>" /></p>
		<p style='text-align: center; line-height: 30px;'><label for="bmpbadge-showbadge">Show User Information? <input class="checkbox" type="checkbox" <?php echo $showbadge; ?> id="bmpbadge-showbadge" name="bmpbadge-showbadge" /></label></p>
		<p style='text-align: center; line-height: 30px;'><label for="bmpbadge-showscores">Show Your Top Ten Scores? <input class="checkbox" type="checkbox" <?php echo $showscores; ?> id="bmpbadge-showscores" name="bmpbadge-showscores" /></label></p>
		<input type="hidden" id="bmpbadge-submit" name="bmpbadge-submit" value="1" />
	<?php
	}
	
	register_sidebar_widget('bmpbadge', 'widget_bmpbadge');
	register_widget_control('bmpbadge', 'widget_bmpbadge_control', 400, 250);
}
	
// Tell Dynamic Sidebar about our new widget and its control
add_action('plugins_loaded', 'widget_bmpbadge_init');

?>
