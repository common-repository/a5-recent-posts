<?php

/**
 *
 * Class Recent Post Widget Admin
 *
 * @ A5 Recent Post Widget
 *
 * building admin page
 *
 */
class RPW_Admin extends A5_OptionPage {
	
	static $options;
	
	function __construct() {
	
		add_action('admin_init', array($this, 'initialize_settings'));
		add_action('admin_menu', array($this, 'add_admin_menu'));
		if (WP_DEBUG == true) add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		self::$options = get_option('rpw_options');
		
	}
	
	/**
	 *
	 * Make debug info collapsable
	 *
	 */ 
	function enqueue_scripts($hook) {
		
		if ('settings_page_a5-recent-posts-settings' != $hook) return;
		
		wp_enqueue_script('dashboard');
		
		if (wp_is_mobile()) wp_enqueue_script('jquery-touch-punch');
		
	}
	
	/**
	 *
	 * Add options-page for single site
	 *
	 */
	function add_admin_menu() {
		
		add_options_page('A5 Recent Post '.__('Settings', 'a5-recent-posts'), '<img alt="" src="'.plugins_url('a5-recent-posts/img/a5-icon-11.png').'"> A5 Recent Posts', 'administrator', 'a5-recent-posts-settings', array($this, 'build_options_page'));
		
	}
	
	/**
	 *
	 * Actually build the option pages
	 *
	 */
	function build_options_page() {
		
		self::open_page('A5 Recent Post Widget', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/recent-post-widget', 'a5-recent-posts'), 'a5-recent-posts', __('Plugin Support', 'a5-recent-posts'));
		
		_e('Style the links of the widget. If you leave this empty, your theme will style the hyperlinks.', 'a5-recent-posts');
		
        self::tag_it(__('Just input something like,', 'a5-recent-posts'), 'p', false, false, true);
				
        self::tag_it(self::tag_it('font-weight: bold;<br />color: #0000ff;<br />text-decoration: underline;', 'strong'), 'p', false, false, true);
		
		self::tag_it(__('to get fat, blue, underlined links.', 'a5-recent-posts'), 'p', false, false, true);
		
        self::tag_it(self::tag_it(__('You most probably have to use &#39;!important&#39; at the end of each line, to make it work.', 'a5-recent-posts'), 'strong'), 'p', false, false, true);
		
		self::open_form('options.php');
		
		settings_fields('rpw_options');
		do_settings_sections('rpw_styles');
		submit_button();
		
		if (WP_DEBUG === true) :
		
			self::open_tab();
			
			self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'a5-recent-posts')));
			
			if (count(self::$options['cache']) > 0) self::sortable('cache-info', self::cache_info(self::$options['cache'], __('Cache', 'a5-recent-posts')));
			
			if (true == WP_DEBUG_LOG) self::sortable('errorlog-info', self::debug_log_info(__('Error Log', 'a5-recent-posts'), 'rpw_debug_settings'));
		
			self::close_tab();
		
		endif;
		
		self::close_page();
		
	}
	
	/**
	 *
	 * Initialize the admin screen of the plugin
	 *
	 */
	function initialize_settings() {
		
		register_setting( 'rpw_options', 'rpw_options', array($this, 'validate') );
		
		add_settings_section('rpw_settings', __('Styling of the links', 'a5-recent-posts'), array($this, 'display_section'), 'rpw_styles');
		
		add_settings_field('rpw_link_style', __('Link style:', 'a5-recent-posts'), array($this, 'link_field'), 'rpw_styles', 'rpw_settings');
		
		add_settings_field('rpw_hover_style', __('Hover style:', 'a5-recent-posts'), array($this, 'hover_field'), 'rpw_styles', 'rpw_settings');
		
		add_settings_field('use_own_css', __('Widget container:', 'a5-recent-posts'), array($this, 'rpw_display_css'), 'rpw_styles', 'rpw_settings', array(__('You can enter your own style for the widgets here. This will overwrite the styles of your theme.', 'a5-recent-posts'), __('If you leave this empty, you can still style every instance of the widget individually.', 'a5-recent-posts')));
		
		add_settings_field('rpw_compress', __('Compress Style Sheet:', 'a5-recent-posts'), array($this, 'compress_field'), 'rpw_styles', 'rpw_settings', array(__('Click here to compress the style sheet.', 'a5-recent-posts')));
		
		add_settings_field('rpw_inline', __('Debug:', 'a5-recent-posts'), array($this, 'inline_field'), 'rpw_styles', 'rpw_settings', array(__('If you can&#39;t reach the dynamical style sheet, you&#39;ll have to diplay the styles inline. By clicking here you can do so. (It might be also much faster in some environments).', 'a5-recent-posts')));
		
		if (self::$options['inline']) add_settings_field('rpw_priority', __('Priority of the inline style:', 'a5-recent-posts'), array($this, 'priority_field'), 'rpw_styles', 'rpw_settings', array(__('This only affects inline styles. Some other plugins could be using the same selectors as this one. In that case, writing your&#39;s later in the code might help.', 'a5-recent-posts')));
		
		$cachesize = count(self::$options['cache']);
		
		$entry = ($cachesize > 1) ? __('entries', 'a5-recent-posts') : __('entry', 'a5-recent-posts');
		
		if ($cachesize > 0) add_settings_field('rpw_reset', sprintf(__('Empty cache (%d %s):', 'a5-recent-posts'), count(self::$options['cache']), $entry), array($this, 'reset_field'), 'rpw_styles', 'rpw_settings', array(__('You can empty the plugin&#39;s cache here, if necessary.', 'a5-recent-posts')));
		
		if (true == WP_DEBUG && true == WP_DEBUG_LOG) :
		
			$filename = WP_CONTENT_DIR.'/debug.log';
			
			$errorlog = file($filename);
			
			$logsize = count($errorlog);
		
			$entry = ($logsize > 1) ? __('entries', 'a5-recent-posts') : __('entry', 'a5-recent-posts');
			
			if ($logsize > 0) :
			
				add_settings_section('rpw_options', sprintf(__('Empty debug log (%d %s):', 'a5-recent-posts'), count($errorlog), $entry), array($this, 'display_reset_section'), 'rpw_debug_settings');
				
				add_settings_field('reset_debug_log', __('You can empty the debug log here, if necessary.', 'a5-recent-posts'), array($this, 'reset_debug_field'), 'rpw_debug_settings', 'rpw_options');
				
			endif;
			
		endif;
		
		add_settings_field('rpw_resize', false, array($this, 'resize_field'), 'rpw_styles', 'rpw_settings');
	
	}
	
	function display_section() {
		
		echo '<p>'.__('Just put some css code here.', 'a5-recent-posts').'</p>';
	
	}
	
	function link_field() {
		
		a5_textarea('link', 'rpw_options[link]', @self::$options['link'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function hover_field() {
		
		a5_textarea('hover', 'rpw_options[hover]', @self::$options['hover'], false, array('cols' => 35, 'rows' => 3));
		
	}
	
	function rpw_display_css($labels) {
		
		echo $labels[0].'</br>'.$labels[1].'</br>';
		
		a5_textarea('css', 'rpw_options[css]', @self::$options['css'], false, array('rows' => 7, 'cols' => 35));
		
	}
	
	function compress_field($labels) {
		
		a5_checkbox('compress', 'rpw_options[compress]', @self::$options['compress'], $labels[0]);
		
	}
	
	function inline_field($labels) {
		
		a5_checkbox('inline', 'rpw_options[inline]', @self::$options['inline'], $labels[0]);
		
	}
	
	function priority_field($labels) {
		
		echo $labels[0].'<br />';
		
		a5_number_field('priority', 'rpw_options[priority]', @self::$options['priority'], false, array('step' => 10));
		
	}
	
	function reset_field($labels) {
		
		a5_checkbox('reset_options', 'rpw_options[reset_options]', @self::$options['reset_options'], $labels[0]);
		
	}
	
	function display_reset_section() {
		
		self::tag_it(__('Empty the debug log.', 'a5-recent-posts'), 'p');
	
	}
	
	function reset_debug_field() {
		
		submit_button(__('OK', 'a5-recent-posts'), 'secondary', 'rpw_options[reset_debug_log]', true, array('id' => 'reset_debug_log'));
		
	}
	
	function resize_field() {
		
		a5_resize_textarea(array('link', 'hover', 'css'), true);
		
	}
		
	function validate($input) {
		
		self::$options['link']=trim($input['link']);
		self::$options['hover']=trim($input['hover']);
		self::$options['css']=trim($input['css']);
		self::$options['compress'] = isset($input['compress']) ? true : false;
		self::$options['priority'] = isset($input['priority']) ? trim($input['priority']) : false;
		
		if(isset($input['inline'])) :
		
			self::$options['inline'] = true;
			
		else :
		
			self::$options['inline'] = false;
			self::$options['priority'] = false;
		
		endif;
		
		if (isset($input['reset_options'])) :
		
			self::$options['cache'] = array();
			
			add_settings_error('rpw_options', 'empty-cache', __('Cache emptied.', 'a5-recent-posts'), 'updated');
			
		endif;
		
		if (isset($input['reset_debug_log'])) :
		
			$filename = WP_CONTENT_DIR.'/debug.log';
			
			file_put_contents($filename, '');
			
			add_settings_error('rpw_options', 'empty-debug', __('Debug Log emptied.', 'a5-recent-posts'), 'updated');
			
			return self::$options;
		
		endif;
		
		self::$options['css_cache'] = '';
		
		return self::$options;
	
	}

} // end of class

?>