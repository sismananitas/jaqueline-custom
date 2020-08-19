<?php
/**
 * Jacqueline Framework: html manipulations
 *
 * @package	jacqueline
 * @since	jacqueline 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Wrappers
-------------------------------------------------------------------------------- */

// Open wrapper tags and add it to stack
if (!function_exists('jacqueline_open_wrapper')) {
	function jacqueline_open_wrapper($tags, $echo=true) {
		if (!is_array($tags) && !empty($tags)) $tags = array($tags);
		$output = '';
		if (is_array($tags) && count($tags) > 0) {
			$cnt = 0;
			foreach ($tags as $tag) {
				jacqueline_storage_set_array('wrappers', '', $tag);
				$output .= "\n".str_repeat("\t", $cnt++).($tag);
			}
		}
		if ($echo) jacqueline_show_layout($output);
		return $output;
	}
}

// Close wrapper and delete it from stack
if (!function_exists('jacqueline_close_wrapper')) {
	function jacqueline_close_wrapper($cnt=1, $echo=true) {
		$output = '';
		$wrappers = jacqueline_storage_get('wrappers');
		$level = count($wrappers);
		$i = 0;
		while ($i < $cnt) {
			if (count($wrappers) == 0) break;
			$open_tag = array_pop($wrappers);
			$tag = explode(' ', $open_tag, 2);
			$close_tag = str_replace('<', '</', $tag[0]).'>';
			$output .= "\n".str_repeat("\t", $level-$i).($close_tag).' <!-- '.($close_tag).' '.($tag[1]).' -->';
			$i++;
		}
		jacqueline_storage_set('wrappers', $wrappers);
		if ($echo) jacqueline_show_layout($output);
		return $output;
	}
}

// Open all wrappers
if (!function_exists('jacqueline_open_all_wrappers')) {
	function jacqueline_open_all_wrappers($echo=true) {
		$output = '';
		$wrappers = jacqueline_storage_get('wrappers');
		for ($i=0; $i<count($wrappers); $i++) {
			$output .= "\n".str_repeat("\t", $i).($wrappers[$i]);
		}
		if ($echo) jacqueline_show_layout($output);
		return $output;
	}
}

// Close all wrappers without stack clear
if (!function_exists('jacqueline_close_all_wrappers')) {
	function jacqueline_close_all_wrappers($echo=true) {
		$output = '';
		$wrappers = jacqueline_storage_get('wrappers');
		for ($i=count($wrappers)-1; $i>=0; $i--) {
			$tag = explode(' ', $wrappers[$i]);
			$output .= "\n".str_repeat("\t", $i).str_replace('<', '</', $tag[0]).'>';
		}
		if ($echo) jacqueline_show_layout($output);
		return $output;
	}
}


/* Tags
-------------------------------------------------------------------------------- */

// Return attrib from tag
if (!function_exists('jacqueline_get_tag_attrib')) {
	function jacqueline_get_tag_attrib($text, $tag, $attr) {
		$val = '';
		if (($pos_start = jacqueline_strpos($text, jacqueline_substr($tag, 0, jacqueline_strlen($tag)-1)))!==false) {
			$pos_end = jacqueline_strpos($text, jacqueline_substr($tag, -1, 1), $pos_start);
			$pos_attr = jacqueline_strpos($text, ' '.($attr).'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += jacqueline_strlen($attr)+3;
				$pos_quote = jacqueline_strpos($text, jacqueline_substr($text, $pos_attr-1, 1), $pos_attr);
				$val = jacqueline_substr($text, $pos_attr, $pos_quote-$pos_attr);
			}
		}
		return $val;
	}
}

// Set (change) attrib from tag
if (!function_exists('jacqueline_set_tag_attrib')) {
	function jacqueline_set_tag_attrib($text, $tag, $attr, $val) {
		if (($pos_start = jacqueline_strpos($text, jacqueline_substr($tag, 0, jacqueline_strlen($tag)-1)))!==false) {
			$pos_end = jacqueline_strpos($text, jacqueline_substr($tag, -1, 1), $pos_start);
			$pos_attr = jacqueline_strpos($text, $attr.'=', $pos_start);
			if ($pos_attr!==false && $pos_attr<$pos_end) {
				$pos_attr += jacqueline_strlen($attr)+2;
				$pos_quote = jacqueline_strpos($text, jacqueline_substr($text, $pos_attr-1, 1), $pos_attr);
				$text = jacqueline_substr($text, 0, $pos_attr) . trim($val) . jacqueline_substr($text, $pos_quote);
			} else {
				$text = jacqueline_substr($text, 0, $pos_end) . ' ' . esc_attr($attr) . '="' . esc_attr($val) . '"' . jacqueline_substr($text, $pos_end);
			}
		}
		return $text;
	}
}




/* CSS values
-------------------------------------------------------------------------------- */

// Return string with margins as classes
if (!function_exists('jacqueline_get_css_position_as_classes')) {
	function jacqueline_get_css_position_as_classes($top='',$right='',$bottom='',$left='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left');
		}
		$output = '';
		if (is_array($top) && count($top) > 0) {
			foreach ($top as $k=>$v) {
				if (!empty($v) && !jacqueline_param_is_inherit($v)) $output .= ($output ? ' ' : '') . 'margin_'.esc_attr($k) . '_' . esc_attr($v);
			}
		}
		return $output;
	}
}

// Return string with position rules for the style attr
if (!function_exists('jacqueline_get_css_position_from_values')) {
	function jacqueline_get_css_position_from_values($top='',$right='',$bottom='',$left='',$width='',$height='') {
		if (!is_array($top)) {
			$top = compact('top','right','bottom','left','width','height');
		}
		$output = '';
		if (is_array($top) && count($top) > 0) {
			foreach ($top as $k=>$v) {
				$imp = jacqueline_substr($v, 0, 1);
				if ($imp == '!') $v = jacqueline_substr($v, 1);
				if ($v != '') $output .= ($k=='width' 
											? 'width' 
											: ($k=='height' 
												? 'height' 
												: 'margin-'.esc_attr($k)
												)
											) . ':' . esc_attr(jacqueline_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
			}
		}
		return $output;
	}
}

// Return string with dimensions rules for the style attr
if (!function_exists('jacqueline_get_css_dimensions_from_values')) {
	function jacqueline_get_css_dimensions_from_values($width='',$height='') {
		if (!is_array($width)) {
			$width = compact('width','height');
		}
		$output = '';
		if (is_array($width) && count($width) > 0) {
			foreach ($width as $k=>$v) {
				$imp = jacqueline_substr($v, 0, 1);
				if ($imp == '!') $v = jacqueline_substr($v, 1);
				if ($v != '') $output .= esc_attr($k) . ':' . esc_attr(jacqueline_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
			}
		}
		return $output;
	}
}

// Return string with paddings for the style attr
if (!function_exists('jacqueline_get_css_paddings_from_values')) {
	function jacqueline_get_css_paddings_from_values($padding_top='',$padding_right='',$padding_bottom='',$padding_left='') {
		if (!is_array($padding_top)) {
			$padding_top = compact('padding_top','padding_right','padding_bottom','padding_left');
		}
		$output = '';
		if (is_array($padding_top) && count($padding_top) > 0) {
			foreach ($padding_top as $k=>$v) {
				if ($v=='') continue;
				$imp = jacqueline_substr($v, 0, 1);
				if ($imp == '!') $v = jacqueline_substr($v, 1);
				$output .= str_replace('_', '-', $k) . ':' . trim(jacqueline_prepare_css_value($v)) . ($imp=='!' ? ' !important' : '') . ';';
			}
		}
		return $output;
	}
}

// Return value for the style attr
if (!function_exists('jacqueline_prepare_css_value')) {
	function jacqueline_prepare_css_value($val) {
		if ($val != '' && !jacqueline_param_is_inherit($val)) {
			$ed = jacqueline_substr($val, -1);
			if ('0'<=$ed && $ed<='9') $val .= 'px';
		}
		return $val;
	}
}

// Multiply css value
if (!function_exists('jacqueline_multiply_css_value')) {
	function jacqueline_multiply_css_value($val, $mult) {
		if ($val != '' && !jacqueline_param_is_inherit($val)) {
			if (preg_match('/([\d\.]+)([a-zA-Z%]*)/', $val, $matches) && !empty($matches[1])) {
				$val = ($matches[1]*$mult) . (!empty($matches[2]) ? $matches[2] : 'px');
			}
		}
		return $val;
	}
}

// Summ css value
if (!function_exists('jacqueline_summ_css_value')) {
	function jacqueline_summ_css_value($val1, $val2) {
		if ( !jacqueline_param_is_inherit($val1) && !jacqueline_param_is_inherit($val2) && ($val1 != '' || $val2 != '') ) {
			if (empty($val1)) $val1 = "0";
			if (empty($val2)) $val2 = "0";
			if (preg_match('/([\d\.]+)([a-zA-Z%]*)/', $val1, $matches1) && !empty($matches1[1]) && preg_match('/([\d\.]+)([a-zA-Z%]*)/', $val2, $matches2) && !empty($matches2[1])) {
				$val1 = ($matches1[1]+$matches2[1]) . (!empty($matches1[2]) ? $matches1[2] : (!empty($matches2[2]) ? $matches2[2] : 'px'));
			}
		}
		return $val1;
	}
}

// Return array with classes from css-file
if (!function_exists('jacqueline_parse_icons_classes')) {
	function jacqueline_parse_icons_classes($css) {
		$rez = array();
		if (!file_exists($css)) return $rez;
		$file = jacqueline_fga($css);
		if (!is_array($file) || count($file) == 0) return $rez;
		foreach ($file as $row) {
			if (jacqueline_substr($row, 0, 1)!='.') continue;
			$name = '';
			for ($i=1; $i<jacqueline_strlen($row); $i++) {
				$ch = jacqueline_substr($row, $i, 1);
				if (in_array($ch, array(':', '{', '.', ' '))) break;
				$name .= $ch;
			}
			if ($name!='') $rez[] = $name;
		}
		return $rez;
	}
}
	
// Return property value for specified selector from css-file
if (!function_exists('jacqueline_get_css_selector_property')) {
	function jacqueline_get_css_selector_property($css, $selector, $prop) {
		$rez = '';
		if (!file_exists($css)) return $rez;
		$file = jacqueline_fga($css);
		if (is_array($file) && count($file) > 0) {
			foreach ($file as $row) {
				if (($pos = jacqueline_strpos($row, $selector))===false) continue;
				if (($pos2 = jacqueline_strpos($row, $prop.':', $pos))!==false && ($pos3 = jacqueline_strpos($row, ';', $pos2))!==false && $pos2 < $pos3) {
					$rez = trim(chop(jacqueline_substr($row, $pos2+jacqueline_strlen($prop)+1, $pos3-$pos2-jacqueline_strlen($prop)-1)));
					break;
				}
			}
		}
		return $rez;
	}
}

// Put theme custom styles into WP inline styles block
if (!function_exists('jacqueline_put_custom_styles')) {
	function jacqueline_put_custom_styles($css, $cond='', $expr='') {
		global $wp_styles;
		if (is_object($wp_styles)) {
			if ($wp_styles->add_data($css, $cond, $expr)) echo 'added';
		}
		return false;
	}
}

// Return minified custom styles to insert it into <head>
if (!function_exists('jacqueline_prepare_custom_styles')) {
	function jacqueline_prepare_custom_styles($minify=true) {
		// Add theme specific custom css
		$css = apply_filters('jacqueline_filter_add_styles_inline', jacqueline_get_custom_styles());
		// Minify css string
		if ($minify) $css = jacqueline_minify_css($css);
		return $css;
	}
}

// Return theme custom styles
if (!function_exists('jacqueline_get_custom_styles')) {
	function jacqueline_get_custom_styles() {
		return jacqueline_storage_get('custom_css');
	}
}

// Add styles to the theme custom styles
if (!function_exists('jacqueline_add_custom_styles')) {
	function jacqueline_add_custom_styles($style) {
	    jacqueline_storage_concat('custom_css', $style);
	}
}

// Minify CSS string
if (!function_exists('jacqueline_minify_css')) {
	function jacqueline_minify_css($css) {
		$css = preg_replace("/\r*\n*/", "", $css);
		$css = preg_replace("/\s{2,}/", " ", $css);
		$css = preg_replace("/\s*>\s*/", ">", $css);
		$css = preg_replace("/\s*:\s*/", ":", $css);
		$css = preg_replace("/\s*{\s*/", "{", $css);
		$css = preg_replace("/\s*;*\s*}\s*/", "}", $css);
        $css = str_replace(', ', ',', $css);
        $css = preg_replace("/(\/\*[\w\'\s\r\n\*\+\,\"\-\.]*\*\/)/", "", $css);
        return $css;
	}
}

// Minify JS string
if (!function_exists('jacqueline_minify_js')) {
	function jacqueline_minify_js($js) {
		$pos = 0;
		while (($pos = jacqueline_strpos($js, '/*', $pos))!==false) {
			if (($pos2 = jacqueline_strpos($js, '*/', $pos))!==false)
				$js = jacqueline_substr($js, 0, $pos) . jacqueline_substr($js, $pos2+2);
			else
				break;
		}
		$pos = -1;
		while (($pos = jacqueline_strpos($js, '//', $pos+1))!==false) {
			if ($js[$pos-1]!='\\') {
				$pos2 = jacqueline_strpos($js, "\n", $pos);
				if ($pos2==false) $pos2 = jacqueline_strlen($js);
				$js = jacqueline_substr($js, 0, $pos) . jacqueline_substr($js, $pos2);
			}
		}
		// Remove spaces before/after {}()
		$js = preg_replace('/\s+/', ' ', $js);
		$js = preg_replace('/([;}{\)\(])\s+/', '$1 ', $js);
		$js = preg_replace('/\s+([;}{\)\(])/', ' $1', $js);
		$js = preg_replace('/(else)\s+/', '$1 ', $js);
		return $js;
	}
}

// Add parameters to URL
if (!function_exists('jacqueline_add_to_url')) {
	function jacqueline_add_to_url($url, $prm) {
		if (is_array($prm) && count($prm) > 0) {
			$separator = jacqueline_strpos($url, '?')===false ? '?' : '&';
			foreach ($prm as $k=>$v) {
				$url .= $separator . urlencode($k) . '=' . urlencode($v);
				$separator = '&';
			}
		}
		return $url;
	}
}



/* Colors manipulations
-------------------------------------------------------------------------------- */

if (!function_exists('jacqueline_hex2rgb')) {
	function jacqueline_hex2rgb($hex) {
		$dec = hexdec(jacqueline_substr($hex, 0, 1)== '#' ? jacqueline_substr($hex, 1) : $hex);
		return array('r'=> $dec >> 16, 'g'=> ($dec & 0x00FF00) >> 8, 'b'=> $dec & 0x0000FF);
	}
}

if (!function_exists('jacqueline_hex2rgba')) {
	function jacqueline_hex2rgba($hex, $alpha) {
		$rgb = jacqueline_hex2rgb($hex);
		return 'rgba('.intval($rgb['r']).','.intval($rgb['g']).','.intval($rgb['b']).','.floatval($alpha).')';
	}
}

if (!function_exists('jacqueline_hex2hsb')) {
	function jacqueline_hex2hsb ($hex) {
		return jacqueline_rgb2hsb(jacqueline_hex2rgb($hex));
	}
}

if (!function_exists('jacqueline_rgb2hsb')) {
	function jacqueline_rgb2hsb ($rgb) {
		$hsb = array();
		$hsb['b'] = max(max($rgb['r'], $rgb['g']), $rgb['b']);
		$hsb['s'] = ($hsb['b'] <= 0) ? 0 : round(100*($hsb['b'] - min(min($rgb['r'], $rgb['g']), $rgb['b'])) / $hsb['b']);
		$hsb['b'] = round(($hsb['b'] /255)*100);
		if (($rgb['r']==$rgb['g']) && ($rgb['g']==$rgb['b'])) $hsb['h'] = 0;
		else if($rgb['r']>=$rgb['g'] && $rgb['g']>=$rgb['b']) $hsb['h'] = 60*($rgb['g']-$rgb['b'])/($rgb['r']-$rgb['b']);
		else if($rgb['g']>=$rgb['r'] && $rgb['r']>=$rgb['b']) $hsb['h'] = 60  + 60*($rgb['g']-$rgb['r'])/($rgb['g']-$rgb['b']);
		else if($rgb['g']>=$rgb['b'] && $rgb['b']>=$rgb['r']) $hsb['h'] = 120 + 60*($rgb['b']-$rgb['r'])/($rgb['g']-$rgb['r']);
		else if($rgb['b']>=$rgb['g'] && $rgb['g']>=$rgb['r']) $hsb['h'] = 180 + 60*($rgb['b']-$rgb['g'])/($rgb['b']-$rgb['r']);
		else if($rgb['b']>=$rgb['r'] && $rgb['r']>=$rgb['g']) $hsb['h'] = 240 + 60*($rgb['r']-$rgb['g'])/($rgb['b']-$rgb['g']);
		else if($rgb['r']>=$rgb['b'] && $rgb['b']>=$rgb['g']) $hsb['h'] = 300 + 60*($rgb['r']-$rgb['b'])/($rgb['r']-$rgb['g']);
		else $hsb['h'] = 0;
		$hsb['h'] = round($hsb['h']);
		return $hsb;
	}
}

if (!function_exists('jacqueline_hsb2rgb')) {
	function jacqueline_hsb2rgb($hsb) {
		$rgb = array();
		$h = round($hsb['h']);
		$s = round($hsb['s']*255/100);
		$v = round($hsb['b']*255/100);
		if ($s == 0) {
			$rgb['r'] = $rgb['g'] = $rgb['b'] = $v;
		} else {
			$t1 = $v;
			$t2 = (255-$s)*$v/255;
			$t3 = ($t1-$t2)*($h%60)/60;
			if ($h==360) $h = 0;
			if ($h<60) { 		$rgb['r']=$t1; $rgb['b']=$t2; $rgb['g']=$t2+$t3; }
			else if ($h<120) {	$rgb['g']=$t1; $rgb['b']=$t2; $rgb['r']=$t1-$t3; }
			else if ($h<180) {	$rgb['g']=$t1; $rgb['r']=$t2; $rgb['b']=$t2+$t3; }
			else if ($h<240) {	$rgb['b']=$t1; $rgb['r']=$t2; $rgb['g']=$t1-$t3; }
			else if ($h<300) {	$rgb['b']=$t1; $rgb['g']=$t2; $rgb['r']=$t2+$t3; }
			else if ($h<360) {	$rgb['r']=$t1; $rgb['g']=$t2; $rgb['b']=$t1-$t3; }
			else {				$rgb['r']=0;   $rgb['g']=0;   $rgb['b']=0; }
		}
		return array('r'=>round($rgb['r']), 'g'=>round($rgb['g']), 'b'=>round($rgb['b']));
	}
}

if (!function_exists('jacqueline_rgb2hex')) {
	function jacqueline_rgb2hex($rgb) {
		$hex = array(
			dechex($rgb['r']),
			dechex($rgb['g']),
			dechex($rgb['b'])
		);
		return '#'.(jacqueline_strlen($hex[0])==1 ? '0' : '').($hex[0]).(jacqueline_strlen($hex[1])==1 ? '0' : '').($hex[1]).(jacqueline_strlen($hex[2])==1 ? '0' : '').($hex[2]);
	}
}

if (!function_exists('jacqueline_hsb2hex')) {
	function jacqueline_hsb2hex($hsb) {
		return jacqueline_rgb2hex(jacqueline_hsb2rgb($hsb));
	}
}


/* Other utils
-------------------------------------------------------------------------------- */

// Decode html-entities in the shortcode parameters
if (!function_exists('jacqueline_html_decode')) {
	function jacqueline_html_decode($prm) {
		if (is_array($prm) && count($prm) > 0) {
			foreach ($prm as $k=>$v) {
				if (is_string($v))
					$prm[$k] = wp_specialchars_decode($v, ENT_QUOTES);
			}
		}
		return $prm;
	}
}
?>