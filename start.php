<?php

namespace AU\EntityMenuDropdown;

const PLUGIN_ID = 'entitymenu_dropdown';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

/**
 *	Init 
 */
function init() {
	elgg_extend_view('css/elgg.css', 'css/entitymenu_dropdown.css');
	elgg_extend_view('js/elgg', 'js/entitymenu_dropdown.js');

	elgg_register_plugin_hook_handler('register', 'menu:entity', __NAMESPACE__ . '\\entity_menu', 9999);
}

/**
 * reorder entity menu items into a dropdown
 * 
 * @param type $hook
 * @param type $type
 * @param array $return
 * @param type $params
 * @return \ElggMenuItem
 */
function entity_menu($hook, $type, $return, $params) {
	static $show_names;
	
	if (!$show_names && $show_name !== 0 && elgg_is_admin_logged_in()) {
		$show_names = (int) elgg_get_plugin_setting('show_names', PLUGIN_ID);
	}
	else {
		$show_names = 0;
	}
	
	if (!is_array($return)) {
		return $return;
	}

	if (count($return) <= 1) {
		return $return;
	}
	
	$bypass = get_bypass_array();

	$children = array(); // this will record the keys that will get moved to children items
	$names = array();
	foreach ($return as $key => $link) {
		$names[] = $link->getName();
		if ($link->inContext() && !in_array($link->getName(), $bypass)) {
			$children[] = $key;
		}
	}

	if (count($children) <= 1) {
		return $return;
	}

	// at this point we know we have items to group in a dropdown
	// create our parent
	$text = elgg_echo('entitymenu_dropdown:options') . ' &#9660;';
	$parent = new \ElggMenuItem('entitymenu_dropdown', $text, '#');
	$parent->addLinkClass('entitymenu-dropdown');
	$parent->setPriority(1000);

	foreach ($children as $key) {
		$parent->addChild($return[$key]);
		unset($return[$key]);
	}

	$return[] = $parent;
	
	if ($show_names) {
		echo '<div class="entitymenu-shownames">' . implode(" | ", $names) . '</div>';
		echo '<div class="clearfloat"></div>';
	}

	return $return;
}

/**
 * get our bypass settings cached
 * 
 * @staticvar type $bypass_array
 * @return type
 */
function get_bypass_array($default = false) {
	static $bypass_array;
	
	if ($bypass_array) {
		return $bypass_array;
	}
	
	$bypass = elgg_get_plugin_setting('bypass', PLUGIN_ID);
	if (is_null($bypass) || $default) {
		$bypass = 'access, delete, download, edit, export, like, published_status, unlike';
	}
	
	$bypass_array = explode(',', $bypass);
	$bypass_array = array_map('trim', $bypass_array);
	
	return $bypass_array;
}