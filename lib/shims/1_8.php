<?php

if (!function_exists('elgg_format_element')) {
	function elgg_format_element($tag_name, array $attributes = array(), $text = '', array $options = array()) {
		if (!is_string($tag_name)) {
			throw new \InvalidArgumentException('$tag_name is required');
		}

		if (isset($options['is_void'])) {
			$is_void = $options['is_void'];
		} else {
			// from http://www.w3.org/TR/html-markup/syntax.html#syntax-elements
			$is_void = in_array(strtolower($tag_name), array(
				'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem',
				'meta', 'param', 'source', 'track', 'wbr'
			));
		}

		if (!empty($options['encode_text'])) {
			$double_encode = empty($options['double_encode']) ? false : true;
			$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8', $double_encode);
		}

		if ($attributes) {
			$attrs = elgg_format_attributes($attributes);
			if ($attrs !== '') {
				$attrs = " $attrs";
			}
		} else {
			$attrs = '';
		}

		if ($is_void) {
			return empty($options['is_xml']) ? "<{$tag_name}{$attrs}>" : "<{$tag_name}{$attrs} />";
		} else {
			return "<{$tag_name}{$attrs}>$text</$tag_name>";
		}
	}
}