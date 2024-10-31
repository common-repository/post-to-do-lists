<?php


/* Add Meta Box
------------------------------------------------------------------------- */
add_action('add_meta_boxes', 'ptdl_add_box');
function ptdl_add_box() {
    add_meta_box('ptdl', __('To-Do Lists', 'ptdl'), 'ptdl_show_box', '', 'normal', 'high');
}

/* The Callback
------------------------------------------------------------------------- */
function ptdl_show_box() {
    global $post;
	$meta = get_post_meta($post->ID, 'ptdl', true);
    // Use nonce for verification
    echo '<input type="hidden" name="ptdl_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<div id="ptdl_table">';
	if (!$meta) {
		echo '<div class="ptdl_container">'; // begin list container
			echo '<a class="ptdl_remove_list" href="#" title="'.__('Remove List', 'ptdl').'">X</a>'; // remove a list
			echo '<table class="form-table">'; // begin list table
				echo '<tr class="ptdl_clear">
						<th><label for="ptdl-0-title">'.__('List Title', 'ptdl').'</label></th>
						<td><input type="text"  name="ptdl[0][title]" id="ptdl-0-title" size="38" /></td>
					  </tr>'; // title
				echo '<tr class="ptdl_clear">
						<th><label for="ptdl-0-items-0-text">'.__('List Items', 'ptdl').'</label></th>
						<td>
							<ul class="ptdl_items">
								<li>
									<span class="ptdl_handle"></span>
									<input type="checkbox" name="ptdl[0][items][0][check]" id="ptdl-0-items-0-check" value="true" />
									<input type="text" name="ptdl[0][items][0][text]" id="ptdl-0-items-0-text" size="30" value="" />
									<input type="date" name="ptdl[0][items][0][date]" id="ptdl-0-items-0-date" size="10" value="" class="ptdl_date" />
									<button class="ptdl_add_item button">+</button>
									<button class="ptdl_remove_item button">-</button>
								</li>
							</ul>
						</td>
					  </tr>'; // items
				echo '<tr class="ptdl_shortcode">
						<th><label for="ptdl-0-shortcode">'.__('Shortcode', 'ptdl').'</label></th>
						<td>
							<input type="text" id="ptdl-0-shortcode" value="[ptdl list=&quot;0&quot;]" disabled="disabled" size="10" />
							<button class="ptdl_add_shortcode button">'.__('Add to Editor', 'ptdl').'</button>
						</td>
					  </tr>'; // shortcode
			echo '</table>'; // end list table
		echo '</div>'; // end list container
	} else {
		$listCount = 0;
		foreach ($meta as $list) {
		$title = $list['title'];
		$items = $list['items'];
		echo '<div class="ptdl_container">'; // begin list container
			echo '<a class="ptdl_remove_list" href="#" title="'.__('Remove List', 'ptdl').'">X</a>'; // remove a list
			echo '<table class="form-table">'; // begin list table
				echo '<tr class="ptdl_clear">
						<th><label for="ptdl-'.$listCount.'-title">'.__('List Title', 'ptdl').'</label></th>
						<td><input type="text"  name="ptdl['.$listCount.'][title]" id="ptdl-'.$listCount.'-title" size="38" value="'.$title.'" /></td>
					  </tr>'; // title
				echo '<tr class="ptdl_clear">
						<th><label for="ptdl-'.$listCount.'-items-0-text">'.__('List Items', 'ptdl').'</label></th>
						<td>
							<ul class="ptdl_items">';
						$itemCount = 0;
						foreach($items as $item) {
							$itemCount++;
							$check = $item['check'];
							$text = $item['text'];
							$date = $item['date'];
							$checked = $check == true ? ' checked="checked"' : '';
							$checkedInput = $check == true ? ' class="ptdl_checked"' : '';
							echo'<li>
									<span class="ptdl_handle"></span>
									<input type="checkbox" name="ptdl['.$listCount.'][items]['.$itemCount.'][check]" id="ptdl-'.$listCount.'-items-'.$itemCount.'-check"'.$checked.' value="true" />
									<input type="text" name="ptdl['.$listCount.'][items]['.$itemCount.'][text]" id="ptdl-'.$listCount.'-items-'.$itemCount.'-text" size="30"'.$checkedInput.' value="'.$text.'" />
									<input type="date" name="ptdl['.$listCount.'][items]['.$itemCount.'][date]" id="ptdl-'.$listCount.'-items-'.$itemCount.'-date" size="10"'.$checkedInput.' value="'.$date.'" class="ptdl_date" />
									<button class="ptdl_add_item button">+</button>
									<button class="ptdl_remove_item button">-</button>
								</li>';
						}
				echo '		</ul>
						</td>
					  </tr>'; // items
				echo '<tr class="ptdl_shortcode">
						<th><label for="ptdl-'.$listCount.'-shortcode">'.__('Shortcode', 'ptdl').'</label></th>
						<td>
							<input type="text" name="ptdl['.$listCount.'][shortcode]" id="ptdl-'.$listCount.'-title" value="[ptdl list=&quot;'.$listCount.'&quot;]" disabled="disabled" size="10" />
							<button class="ptdl_add_shortcode button">'.__('Add to Editor', 'ptdl').'</button>
						</td>
					  </tr>'; // shortcode
			echo '</table>'; // end list table
		echo '</div>'; // end list container
		$listCount++;
		} // $list
	}
		echo '<div class="clear"></div>
			<button class="ptdl_add_list button-primary">'.__('Add List', 'ptdl').'</button>'; // add a list
    echo '</div>';
}


/* Save the Data
------------------------------------------------------------------------- */
add_action('save_post', 'ptdl_save_data');
// Save data from meta box
function ptdl_save_data($post_id) {
	// verify nonce
	if (!wp_verify_nonce($_POST['ptdl_meta_box_nonce'], basename(__FILE__))) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	$old = get_post_meta($post_id, 'ptdl', true);
	$new = $_POST['ptdl'];
	if ($new && $new != $old) {
		update_post_meta($post_id, 'ptdl', $new);
	} elseif ('' == $new && $old) {
		delete_post_meta($post_id, 'ptdl', $old);
	}
}
?>