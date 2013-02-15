elgg.provide('elgg.entitymenu_dropdown');

elgg.entitymenu_dropdown.init = function() {

  elgg.entitymenu_dropdown.position();
  
  $(document).ajaxComplete( function() {
	elgg.entitymenu_dropdown.position();
  });
  
}

elgg.entitymenu_dropdown.position = function() {
$('.elgg-menu-item-entitymenu-dropdown').each( function(index) {
	var dropdown = $(this).children('ul.elgg-menu').eq(0);
	var width = $(this).width();
	var offset = dropdown.width() - width;
	
	if (offset > 0) {
	  dropdown.css('marginLeft', '-'+offset+'px');
	}
	
  });
}

elgg.register_hook_handler('init', 'system', elgg.entitymenu_dropdown.init);