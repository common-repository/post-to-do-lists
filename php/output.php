<?

// create list
function ptdl_list($listID) {
	global $post;
	
	$meta = get_post_meta($post->ID, 'ptdl', true);
	$theList = $meta[$listID];
	
	if (!empty($theList)) {
	
		$title = $theList['title'];
		$items = $theList['items'];
		
		$output = '<h3>'.$title.'</h3><ol>';
		foreach ($items as $item) {
			$check = $item['check'];
			$text = $item['text'];
			$date = $item['date'];
			if (!empty($date)) {
				$date = strtotime($date);
				$date = date('F j, Y', $date);
				$date = ' <span class="ptdl_date">'.$date.'</span>';
			}
			
			$class = $check == true ? ' class="ptdl_checked"' : '';
			$output .= '<li'.$class.'>'.$text.$date.'</li>';
		}
		$output .= '</ol>';
		
	} else {
		$output = '<p class="ptdl_error">'.__('List was not found; please check your shortcode.', 'ptdl').'</p>';
	}
	
	return $output;
}


// shortcode
function ptdl_shortcode($atts, $content = null) {
	extract( shortcode_atts( array(
	  'list' => ''
      ), $atts ) );
	  //$list = $list -1;
	return ptdl_list($list);
}
add_shortcode('ptdl', 'ptdl_shortcode');

?>