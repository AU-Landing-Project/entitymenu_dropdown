<?php

namespace AU\EntityMenuDropdown;

const PLUGIN_ID = 'entitymenu_dropdown';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

/**
 *	Init 
 */
function init() {
	elgg_extend_view('css/elgg', 'css/entitymenu_dropdown');
	elgg_extend_view('js/elgg', 'js/entitymenu_dropdown');

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
	
	if (!is_array($return)) {
		return $return;
	}

	if (count($return) <= 1) {
		return $return;
	}
	
	$bypass = get_bypass_array();

	$children = array(); // this will record the keys that will get moved to children items
	foreach ($return as $key => $link) {
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

	return $return;
}

/**
 * get our bypass settings cached
 * 
 * @staticvar type $bypass_array
 * @return type
 */
function get_bypass_array() {
	static $bypass_array;
	
	if ($bypass_array) {
		return $bypass_array;
	}
	
	$bypass = elgg_get_plugin_setting('bypass', PLUGIN_ID);
	if (!$bypass) {
		$bypass = 'access, au_sets_pin, delete, download, export, ical_export, likes, published_status, tagging, views';
	}
	
	$bypass_array = explode(',', $bypass);
	array_walk($bypass_array, function($v) { return trim($v); });
	
	return $bypass_array;
}