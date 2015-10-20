<?php

namespace AU\EntityMenuDropdown;

$default = get_bypass_array(true);
$list = implode(', ', $default);

echo '<label>' . elgg_echo('entitymenu_dropdown:ignore') . '</label>';

echo elgg_view('input/text', array(
	'name' => 'params[bypass]',
	'value' => is_null($vars['entity']->bypass) ? $list : $vars['entity']->bypass,
));

echo elgg_view('output/longtext', array(
	'value' => elgg_echo('entitymenu_dropdown:bypass:default', array($list)),
	'class' => 'elgg-text-help'
));

echo elgg_view('input/dropdown', array(
	'name' => 'params[show_names]',
	'value' => $vars['entity']->show_names ? $vars['entity']->show_names : 0,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes')
	)
));

echo ' ' . elgg_echo('entitymenu_dropdown:label:show_names');
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('entitymenu_dropdown:show_names:help'),
	'class' => 'elgg-text-help'
));