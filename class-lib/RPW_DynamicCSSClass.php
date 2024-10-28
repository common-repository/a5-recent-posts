<?php

/**
 *
 * Class RPW Dynamic CSS
 *
 * Extending A5 Dynamic Files
 *
 * Presses the dynamical CSS for the A5 Recent Post Widget into a virtual style sheet
 *
 */

class RPW_DynamicCSS extends A5_DynamicFiles {
	
	private static $options;
	
	function __construct() {
		
		self::$options =  get_option('rpw_options');
		
		if (!array_key_exists('inline', self::$options)) self::$options['inline'] = false;
		
		if (!array_key_exists('priority', self::$options)) self::$options['priority'] = false;
		
		if (!array_key_exists('compress', self::$options)) self::$options['compress'] = true;
		
		$this->a5_styles('wp', 'all', self::$options['inline'], self::$options['priority']);
		
		$rpw_styles = self::$options['css_cache'];
		
		if (!$rpw_styles) :
		
			$eol = (self::$options['compress']) ? '' : "\n";
			$tab = (self::$options['compress']) ? '' : "\t";
			
			$css_selector = 'widget_a5_recent_post_widget[id^="a5_recent_post_widget"]';
			
			$rpw_styles = (!self::$options['compress']) ? $eol.'/* CSS portion of the A5 Recent Post Widget */'.$eol.$eol : '';
			
			if (!empty(self::$options['css'])) :
			
				$style = str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['css']));
				
				$rpw_styles .= parent::build_widget_css($css_selector, '', self::$options['compress']).'{'.$eol.$tab.$style.$eol.'}'.$eol;
				
			endif;
				
			$rpw_styles .= parent::build_widget_css($css_selector, 'img', self::$options['compress']).'{'.$eol.$tab.'height: auto;'.$eol.$tab.'max-width: 100%;'.$eol.'}'.$eol;
			
			if (!empty (self::$options['link'])) :
			
				$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['link']));
			
				$rpw_styles .= parent::build_widget_css($css_selector, 'a', self::$options['compress']).'{'.$eol.$tab.$style.$eol.'}'.$eol;
				
			endif;
			
			if (!empty (self::$options['hover'])) :
			
				$style=str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['hover']));
			
				$rpw_styles .= parent::build_widget_css($css_selector, 'a:hover', self::$options['compress']).'{'.$eol.$tab.$style.$eol.'}'.$eol;
				
			endif;
			
			self::$options['css_cache'] = $rpw_styles;
			
			update_option('rpw_options', self::$options);
			
		endif;
		
		parent::$wp_styles .= $rpw_styles;

	}
	
} // RPW_Dynamic CSS

?>