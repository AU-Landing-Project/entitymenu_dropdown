<?php

$default = 'access, au_sets_pin, delete, ical_export, likes, published_status, export, download, views';

echo elgg_echo('entitymenu_dropdown:ignore') . '<br>';

echo elgg_view('input/text', array(
	'name' => 'params[bypass]',
	'value' => $vars['entity']->bypass ? $vars['entity']->bypass : $default,
));

echo elgg_view('output/longtext', array(
	'value' => elgg_echo('entitymenu_dropdown:bypass:default', array($default)),
	'class' => 'elgg-subtext'
));