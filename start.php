<?php

function entitymenu_dropdown_init() {
  elgg_extend_view('css/elgg', 'entitymenu_dropdown/css');
  elgg_extend_view('js/elgg', 'entitymenu_dropdown/js');
  elgg_register_plugin_hook_handler('register', 'menu:entity', 'entitymenu_dropdown_registration', 9999);
}


function entitymenu_dropdown_registration($hook, $type, $return, $params) {
  if (is_array($return) && count($return) > 1) {

	static $ENTITYMENU_DROPDOWN_BYPASS_ARRAY;
	
	if (!$ENTITYMENU_DROPDOWN_BYPASS_ARRAY) {
	  $bypass = elgg_get_plugin_setting('bypass', 'entitymenu_dropdown');
	  if (!$bypass) {
		$bypass = 'access, au_sets_pin, delete, download, export, ical_export, likes, published_status, views';
	  }
	  $ENTITYMENU_DROPDOWN_BYPASS_ARRAY = explode(',', $bypass);
	  foreach ($ENTITYMENU_DROPDOWN_BYPASS_ARRAY as $key => $name) {
		$ENTITYMENU_DROPDOWN_BYPASS_ARRAY[$key] = trim($name);
	  }
	}

	$children = array(); // this will record the keys that will get moved to children items
	foreach ($return as $key => $link) {
	  if ($link->inContext() && !in_array($link->getName(), $ENTITYMENU_DROPDOWN_BYPASS_ARRAY)) {
		$children[] = $key;
	  }
	}
	
	if (count($children) <= 1) {
	  return $return;
	}
	
	// at this point we know we have items to group in a dropdown
	// create our parent
	$text = elgg_echo('entitymenu_dropdown:options') . '<span class="elgg-icon elgg-icon-round-plus"></span>';
	$parent = new ElggMenuItem('entitymenu_dropdown', $text, '#');
	$parent->addLinkClass('entitymenu-dropdown');
	$parent->setPriority(1000);
	
	foreach ($children as $key) {
	  $parent->addChild($return[$key]);
	  unset($return[$key]);
	}
	
	$return[] = $parent;
  }
  
  return $return;
}


elgg_register_event_handler('init', 'system', 'entitymenu_dropdown_init');