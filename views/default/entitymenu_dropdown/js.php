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
	$('body').prepend('<div id="entitymenu-dropdown-menu" class="hidden"><ul></ul></div>');
	var modal  = $('#entitymenu-dropdown-menu');
	modal.children('ul').eq(0).html(html);
	
	
	var left = Math.round(offset.left - modal.width() + $(this).width());
	var top = Math.round(offset.top + $(this).height() - 2);
		
	modal.css('marginLeft', left+'px');
	modal.css('marginTop', top+'px');
	modal.removeClass('hidden');
  });
  
  $('.elgg-menu-item-entitymenu-dropdown, #entitymenu-dropdown-menu').live('mouseleave', function() {
	setTimeout('elgg.entitymenu_dropdown.hovercheck()', 100);
  });
  
}

elgg.entitymenu_dropdown.hovercheck = function() {
  if (elgg.entitymenu_dropdown.parent.is(':hover')) {
	console.log('parent hover');
  }
  
  if ($('#entitymenu-dropdown-menu').is(':hover')) {
	console.log('menu hover');
  }
  
  if (elgg.entitymenu_dropdown.parent.is(':hover') || $('#entitymenu-dropdown-menu').is(':hover')) {
	return;
  }
  
  elgg.entitymenu_dropdown.reset();
}

elgg.entitymenu_dropdown.reset = function() {
  if (!elgg.entitymenu_dropdown.entity) {
	return true;
  }
  
  var html = $('#entitymenu-dropdown-menu ul').html();
  
  if (!html) {
	return;
  }
  
  elgg.entitymenu_dropdown.entity.html(html);
  $('#entitymenu-dropdown-menu').remove();
  
  $('a.entitymenu-dropdown.active').removeClass('active');
}

elgg.register_hook_handler('init', 'system', elgg.entitymenu_dropdown.init);