elgg.provide('elgg.entitymenu_dropdown');

elgg.entitymenu_dropdown.init = function() {

  // global holding currently active html
  elgg.entitymenu_dropdown.html = '';
  
  // global holding currently active entity
  elgg.entitymenu_dropdown.entity = false;
  
  // global holding the currently active parent
  elgg.entitymenu_dropdown.parent = false;

  $('.elgg-menu-item-entitymenu-dropdown').live('mouseenter click', function(e) {
	e.preventDefault();
	
	var entity = $(this).children('ul').eq(0);
	var html = entity.html();
	var offset = $(this).offset();
	
	if (!html) {
	  return;
	}
	
	// reset existing active
	elgg.entitymenu_dropdown.reset();
	
	entity.html('');
	
	// set new globals
	elgg.entitymenu_dropdown.parent = $(this);
	elgg.entitymenu_dropdown.entity = entity;
	elgg.entitymenu_dropdown.html = html;
	
	$(this).children('.entitymenu-dropdown').eq(0).addClass('active');
	
	// create our dropdown
	$('body').prepend('<div id="au-sets-hover-menu" class="hidden"><ul></ul></div>');
	var modal  = $('#au-sets-hover-menu');
	modal.children('ul').eq(0).html(html);
	
	
	var left = Math.round(offset.left - modal.width() + $(this).width());
	var top = Math.round(offset.top + $(this).height() - 2);
		
	modal.css('marginLeft', left+'px');
	modal.css('marginTop', top+'px');
	modal.removeClass('hidden');
  });
  
  $('.elgg-menu-item-entitymenu-dropdown, #au-sets-hover-menu').live('mouseleave', function() {
	setTimeout('elgg.entitymenu_dropdown.hovercheck()', 100);
  });
  
}

elgg.entitymenu_dropdown.hovercheck = function() {
  if (elgg.entitymenu_dropdown.parent.is(':hover') || $('#au-sets-hover-menu').is(':hover')) {
	return;
  }
  
  elgg.entitymenu_dropdown.reset();
}

elgg.entitymenu_dropdown.reset = function() {
  if (!elgg.entitymenu_dropdown.entity) {
	return true;
  }
  
  $('#au-sets-hover-menu').remove();
  elgg.entitymenu_dropdown.entity.html(elgg.entitymenu_dropdown.html);
  
  $('a.entitymenu-dropdown.active').removeClass('active');
}

elgg.register_hook_handler('init', 'system', elgg.entitymenu_dropdown.init);