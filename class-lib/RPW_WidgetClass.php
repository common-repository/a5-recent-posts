<?php

/**
 *
 * Class RPW Widget
 *
 * @ A5 Recent Post Widget
 *
 * building the actual widget
 *
 */ 
class A5_Recent_Post_Widget extends A5_Widget {
	
	private static $options;
 
	function __construct() {
 
		$widget_opts = array( 'description' => __('You can display the most recent post(s) in this widget. Define, on what pages the widget will show.', 'a5-recent-posts') );
		$control_opts = array( 'width' => 400 );
		
		parent::__construct(false, $name = 'A5 Recents Post', $widget_opts, $control_opts);
		
		self::$options = get_option('rpw_options');
	
	}
	
	function form($instance) {
	
		// setup the default settings
		
		$defaults = array(
			'title' => NULL,
			'thumb' => false,
			'width' => get_option('thumbnail_size_w'),
			'link' => NULL,
			'target' => false,
			'headline' => NULL,
			'noshorts' => false,
			'readmore' => false,
			'filter' => false,
			'rmtext' => NULL,
			'rmclass' => NULL,
			'style' => NULL,
			'homepage' => 1,
			'frontpage' => 1,
			'page' => 1,
			'category' => false,
			'single' => 1,
			'date' => false,
			'archive' => false,
			'tag' => false,
			'attachment' => false,
			'taxonomy' => false,
			'author' => false,
			'search' => false,
			'not_found' => false,
			'login_page' => false,
			'show_date' => NULL,
			'h' => 3,
			'alignment' => NULL,
			'imgborder' => NULL,
			'posts_per_page' => 1,
			'wordcount' => 3,
			'words' => false,
			'sticky' => false
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = esc_attr($instance['title']);
		$thumb = esc_attr($instance['thumb']);
		$width = esc_attr($instance['width']);
		$link = esc_attr($instance['link']);
		$target = esc_attr($instance['target']);
		$headline = esc_attr($instance['headline']);
		$noshorts = esc_attr($instance['noshorts']);
		$filter = esc_attr($instance['filter']);
		$readmore = esc_attr($instance['readmore']);
		$rmtext = esc_attr($instance['rmtext']);
		$rmclass = esc_attr($instance['rmclass']);
		$style = esc_attr($instance['style']);
		$homepage = $instance['homepage'];
		$frontpage = $instance['frontpage'];
		$page = $instance['page'];
		$category = $instance['category'];
		$single = $instance['single'];
		$date = $instance['date'];
		$archive = $instance['archive'];
		$tag = $instance['tag'];
		$attachment = $instance['attachment'];
		$taxonomy = $instance['taxonomy'];
		$author = $instance['author'];
		$search = $instance['search'];
		$not_found = $instance['not_found'];
		$login_page = $instance['login_page'];
		$show_date=esc_attr($instance['show_date']);
		$h=esc_attr($instance['h']);
		$alignment=esc_attr($instance['alignment']);
		$imgborder = esc_attr($instance['imgborder']);
		$posts_per_page = esc_attr($instance['posts_per_page']);
		$wordcount = esc_attr($instance['wordcount']);
		$words = esc_attr($instance['words']);
		$sticky = esc_attr($instance['sticky']);
		
		$link_options = array (array('post', __('The post', 'a5-recent-posts')), array('extern', __('External link', 'a5-recent-posts')), array('page', __('The attachment page', 'a5-recent-posts')), array('file', __('The attachment file', 'a5-recent-posts')), array('none', __('Don&#39;t link', 'a5-recent-posts')));
		
		$options = array (array('top', __('Above thumbnail', 'a5-recent-posts')) , array('bottom', __('Under thumbnail', 'a5-recent-posts')), array('middel', __('Under date', 'a5-recent-posts')), array('none', __('Don&#39;t show title', 'a5-recent-posts')));
		
		$date_options = array (array('top', __('Above post', 'a5-recent-posts')), array('middel', __('Under thumbnail', 'a5-recent-posts')), array('bottom', __('Under post', 'a5-recent-posts')), array('none', __('Don&#39;t show date', 'a5-recent-posts')));
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		a5_text_field($base_id.'title', $base_name.'[title]', $title, __('Title:', 'a5-recent-posts'), array('space' => true, 'class' => 'widefat'));
		a5_number_field($base_id.'posts_per_page', $base_name.'[posts_per_page]', $posts_per_page, __('How many posts should be displayed in the widget', 'a5-recent-posts'), array('space' => true, 'size' => 4, 'step' => 1, 'min' => 1));
		a5_checkbox($base_id.'sticky', $base_name.'[sticky]', $target, __('Check to have only sticky posts.', 'a5-recent-posts'), array('space' => true));
		a5_number_field($base_id.'width', $base_name.'[width]', $width, __('Width of the thumbnail (in px):', 'a5-recent-posts'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_select($base_id.'link', $base_name.'[link]', $link_options, $link, __('Choose here to what you want the widget to link to. It will link to the post by default.', 'a5-recent-posts'), false, array('space' => true));
		a5_checkbox($base_id.'target', $base_name.'[target]', $target, __('Check to open the link in another browser window.', 'a5-recent-posts'), array('space' => true));
		a5_checkbox($base_id.'thumb', $base_name.'[thumb]', $thumb, sprintf(__('Check to %snot%s display the thumbnail of the post.', 'a5-recent-posts'), '<strong>', '</strong>'), array('space' => true));
		a5_text_field($base_id.'imgborder', $base_name.'[imgborder]', $imgborder, sprintf(__('If wanting a border around the image, write the style here. %s would make it a black border, 1px wide.', 'a5-recent-posts'), '<strong>1px solid #000000</strong>'), array('space' => true, 'class' => 'widefat'));
		a5_select($base_id.'headline', $base_name.'[headline]', $options, $headline, __('Choose, whether or not to display the title and whether it comes above or under the thumbnail.', 'a5-recent-posts'), false, array('space' => true));
		parent::select_heading($instance);
		a5_select($base_id.'show_date', $base_name.'[show_date]', $date_options, $show_date, __('Choose, whether or not to display the publishing date and whether it comes above or under the post.', 'a5-recent-posts'), false, array('space' => true));
		parent::textalign($instance);
		a5_checkbox($base_id.'noshorts', $base_name.'[noshorts]', $noshorts, __('Check to suppress shortcodes in the widget (in case the content is showing).', 'a5-recent-posts'), array('space' => true));
		a5_checkbox($base_id.'filter', $base_name.'[filter]', $filter, __('Check to return the excerpt unfiltered (might avoid interferences with other plugins).', 'a5-recent-posts'), array('space' => true));
		a5_number_field($base_id.'wordcount', $base_name.'[wordcount]', $wordcount, __('In case there is no excerpt defined, how many sentences are displayed:', 'a5-recent-posts'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'words', $base_name.'[words]', $words, __('Check to display words instead of sentences.', 'a5-recent-posts'), array('space' => true));
		parent::read_more($instance);
		parent::page_checkgroup($instance);
		a5_textarea($base_id.'style', $base_name.'[style]', $style, sprintf(__('Here you can finally style the widget. Simply type something like%sto get just a gray outline and a padding of 10 px. If you leave that section empty, your theme will style the widget.', 'a5-recent-posts'), '<br /><strong>border: 2px solid;<br />border-color: #cccccc;<br />padding: 10px;</strong><br />'), array('space' => true, 'class' => 'widefat', 'style' => 'height: 60px;'));
		a5_resize_textarea($base_id.'style', true);
	
	} // form
	
	function update($new_instance, $old_instance) {
		
		unset(self::$options['cache'][$this->number]);
		
		global $wpdb;
		
		$update_args = array('option_value' => serialize(self::$options));
		
		$result = $wpdb->update( $wpdb->options, $update_args, array( 'option_name' => 'rpw_options' ) );
	
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']); 
		$instance['thumb'] = @$new_instance['thumb'];	 
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['target'] = @$new_instance['target'];
		$instance['headline'] = strip_tags($new_instance['headline']);
		$instance['noshorts'] = @$new_instance['noshorts'];
		$instance['filter'] = @$new_instance['filter'];
		$instance['readmore'] = @$new_instance['readmore'];
		$instance['rmtext'] = strip_tags($new_instance['rmtext']);
		$instance['rmclass'] = strip_tags($new_instance['rmclass']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['homepage'] = @$new_instance['homepage'];
		$instance['frontpage'] = @$new_instance['frontpage'];
		$instance['page'] = @$new_instance['page'];
		$instance['category'] = @$new_instance['category'];
		$instance['single'] = @$new_instance['single'];
		$instance['date'] = @$new_instance['date'];
		$instance['archive'] = @$new_instance['archive'];
		$instance['tag'] = @$new_instance['tag'];
		$instance['attachment'] = @$new_instance['attachment'];
		$instance['taxonomy'] = @$new_instance['taxonomy'];
		$instance['author'] = @$new_instance['author'];
		$instance['search'] = @$new_instance['search'];
		$instance['not_found'] = @$new_instance['not_found'];
		$instance['login_page'] = @$new_instance['login_page'];
		$instance['show_date'] = strip_tags($new_instance['show_date']);
		$instance['h'] = strip_tags($new_instance['h']);
		$instance['alignment'] = strip_tags($new_instance['alignment']);
		$instance['imgborder'] = strip_tags($new_instance['imgborder']);
		$instance['posts_per_page'] = strip_tags($new_instance['posts_per_page']);
		$instance['wordcount'] = strip_tags($new_instance['wordcount']);
		$instance['words'] = @$new_instance['words'];
		$instance['sticky'] = @$new_instance['sticky'];
		
		return $instance;
	
	} // update
 
	function widget($args, $instance) {
		
		$show_widget = parent::check_output($instance);
	
		if ($show_widget) :
		
			// the widget is displayed	
			
			extract( $args );
			
			$title = apply_filters('widget_title', $instance['title']);
			
			if (!empty($instance['style'])) :
			
				$style=str_replace(array("\r\n", "\n", "\r"), ' ', $instance['style']);
				
				$before_widget = str_replace('>', 'style="'.$style.'">', $before_widget);
			
			endif;
			
			$eol = "\n";
			
			// widget starts
			
			echo $before_widget.$eol;
			
			if ( $title ) echo $before_title . $title . $after_title . $eol;
			
			$rpw_target = (empty ($instance['target'])) ? '' : ' target="_blank"';
			
			/* This is the actual function of the plugin, it fills the widget with the customized post */
			
			global $wp_query, $post;
			
			$rpw_args['posts_per_page'] = $instance['posts_per_page'];
			
			if ($instance['sticky']) :
			
				$rpw_args['post__in'] = get_option('sticky_posts');
			
			endif;
			
			if (is_single()) $rpw_args['post__not_in'] = array($wp_query->get_queried_object_id());
			
			$rpw_posts = new WP_Query($rpw_args);
			
			while($rpw_posts->have_posts()) :
			
				$rpw_posts->the_post();
				
				setup_postdata($post);
				
				switch ($instance['link']) :
				
					case 'none' :
					
						$rpw_link = false;
						
						break;
				
					case 'post' :
					
						$rpw_link = get_permalink();
						
						break;
				
					case 'extern' :
					
						$rpw_link = preg_match_all('/<a.+href=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
						$rpw_link = $matches [1] [0];
						
						break;
					
					default :
					
						$args = array(
							'post_type' => 'attachment',
							'posts_per_page' => 1,
							'post_status' => null,
							'post_parent' => $post->ID,
							'order' => 'ASC'
						);
						
						$rpw_attachments = get_posts( $args );
						
						if ( $rpw_attachments ) :
						
							foreach ( $rpw_attachments as $attachment ) :
							
								if ($instance['link'] == 'page') $rpw_link = get_attachment_link( $attachment->ID );
								
								if ($instance['link'] == 'file') $rpw_link = wp_get_attachment_url( $attachment->ID );
							
							endforeach;
							
						else :
						
							$rpw_link = false;
						
						endif;
						
						break;
					
					endswitch;
					
				if (isset(self::$options['cache'][$this->number][$post->ID]['tags'])) :
				
					$rpw_tags = self::$options['cache'][$this->number][$post->ID]['tags'];
				
				else :
				
					$rpw_tags = A5_Image::tags();
					
					self::$options['cache'][$this->number][$post->ID]['tags'] = $rpw_tags;
					
					update_option('rpw_options', self::$options);
					
				endif;
				
				$rpw_image_alt = $rpw_tags['image_alt'];
				$rpw_image_title = $rpw_tags['image_title'];
				$rpw_title_tag = $rpw_tags['title_tag'];
				
				$rpw_style = ($instance['alignment'] != 'notext' && $instance['alignment'] != 'none') ? ' style="text-align: '.$instance['alignment'].';"' : '';
				
				// headline, if wanted
				
				if ($instance['headline'] != 'none') :
				
					$rpw_link_tag = ($rpw_link) ? $eol.'<a href="'.$rpw_link.'" title="'.$rpw_title_tag.'"'.$rpw_target.'>'.get_the_title().'</a>'.$eol : $eol.get_the_title().$eol;
				
					$rpw_headline = '<h'.$instance['h'].$rpw_style.'>'.$rpw_link_tag.'</h'.$instance['h'].'>';
					
				endif;
				
				// date, if wanted
				
				if ($instance['show_date'] != 'none') $post_date = $eol.'<p'.$rpw_style.'>'.get_the_date().'</p>';
			
				// thumbnail, if wanted
				
				if (!$instance['thumb']) :
				
					if (isset(self::$options['cache'][$this->number][$post->ID]['image'])) :
					
						$rpw_image = self::$options['cache'][$this->number][$post->ID]['image'];
					
					else :
						
						$rpw_image = false;
						
						$args = array (
							'id' => $post->ID,
							'width' => $instance['width']
						);
						   
						$rpw_image_info = A5_Image::thumbnail($args);
						
						if ($rpw_image_info) :
						
							$rpw_thumb = $rpw_image_info[0];
							
							$rpw_width = $rpw_image_info[1];
					
							$rpw_height = ($rpw_image_info[2]) ? ' height="'.$rpw_image_info[2].'" ': '';
							
							$rpw_imgborder = (!empty($instance['imgborder'])) ? ' border: '.$instance['imgborder'].';' : '';
							
							$rpw_float = ($instance['alignment'] != 'notext') ? $instance['alignment'] : 'none';
							
							$rpw_margin = '';
								
							if ($instance['alignment'] == 'left') $rpw_margin = ' margin-right: 1em;';
							
							if ($instance['alignment'] == 'right') $rpw_margin = ' margin-left: 1em;';
							
							$rpw_img_tag = '<img title="'.$rpw_image_title.'" src="'.$rpw_thumb.'" alt="'.$rpw_image_alt.'" class="wp-post-image" width="'.$rpw_width.'"'.$rpw_height.'style="float: '.$rpw_float.';'.$rpw_margin.$rpw_imgborder.'" />';
							
						else : 
						
							$rpw_img_tag = '';
							
						endif;
					
						if (!empty($rpw_img_tag)) :
								
							$rpw_image = ($rpw_link) ? '<a href="'.$rpw_link.'">'.$rpw_img_tag.'</a>'.$eol : $rpw_img_tag.$eol;
							
							if ($instance['alignment'] == 'none' || $instance['alignment'] == 'notext') $rpw_image .= '<div style="clear: both;"></div>'.$eol;
							
						endif;
							
						self::$options['cache'][$this->number][$post->ID]['image'] = $rpw_image;
						
						update_option('rpw_options', self::$options);
						
					endif;
					
				endif;
				
				// excerpt if wanted
				
				if ($instance['alignment'] != 'notext') :
				
					if (isset(self::$options['cache'][$this->number][$post->ID]['text'])) :
					
						$rpw_text = self::$options['cache'][$this->number][$post->ID]['text'];
					
					else :
				
						$rmtext = ($instance['rmtext']) ? $instance['rmtext'] : '[&#8230;]';
						
						$shortcode = ($instance['noshorts']) ? false : true;
						
						$filter = ($instance['filter']) ? false : true;
					
						$args = array(
							'excerpt' => $post->post_excerpt,
							'content' => $post->post_content,
							'shortcode' => $shortcode,
							'link' => get_permalink(),
							'title' => $rpw_title_tag,
							'count' => $instance['wordcount'],
							'readmore' => $instance['readmore'],
							'rmtext' => $rmtext,
							'class' => $instance['rmclass'],
							'filter' => $filter
						);
						
						if (!empty($instance['words'])) $args['type'] = 'words';
						
						$rpw_text = A5_Excerpt::text($args);
						
						self::$options['cache'][$this->number][$post->ID]['text'] = $rpw_text;
					
						update_option('rpw_options', self::$options);
						
					endif;
				
				endif;
				
				// writing the stuff in the widget
				
				if ($instance['headline'] == 'top') echo $rpw_headline.$eol;
				
				if ($instance['show_date'] == 'top') echo $post_date.$eol;
				
				if ($instance['show_date'] == 'top' && $instance['headline'] == 'middel') echo $rpw_headline.$eol;
				
				if (!$instance['thumb']) echo $rpw_image;
				
				if ($instance['headline'] == 'bottom') echo $rpw_headline.$eol;
				
				if ($instance['show_date'] == 'middel') echo $post_date.$eol;
				
				if ($instance['show_date'] == 'middel' && $instance['headline'] == 'middel') echo $rpw_headline.$eol;
				
				if ($instance['alignment'] == 'left' || $instance['alignment'] == 'right') echo $eol.do_shortcode($rpw_text).$eol;
				
				if ($instance['alignment'] == 'none') echo do_shortcode($rpw_text).$eol;
				
				if ($instance['show_date'] == 'bottom') echo $post_date.$eol;
				
				if ($instance['show_date'] == 'bottom' && $instance['headline'] == 'middel') echo $rpw_headline.$eol;
			
			endwhile;
			
			// Restore original Post Data
			
			wp_reset_postdata();
			
			echo $after_widget;
		
		else:
		
			echo '<!-- A5 Recent Post Widget is not setup for this view. -->';
		
		endif;
		
	} // widget
 
} // end of class
add_action('widgets_init', create_function('', 'return register_widget("A5_Recent_Post_Widget");'));

?>