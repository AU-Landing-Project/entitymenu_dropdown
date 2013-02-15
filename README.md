entitymenu_dropdown
===================

Turns an overcrowded entity menu into a neat dropdown

Note: uses css styles to fit the Athabasca University page, you may want to change that
to fit your own site.  Styles can be found in views/default/entitymenu_dropdown/css.php

Also Note: due to the core theme insane over-usage of overflow:hidden the dropdown keeps
getting hidden inside container elements.  This presents mostly in lists.  Therefore the
css contained forces .elgg-body to have visible overflow when in lists, which helps this
work.  It may cause issues with your theme, you'll have to figure that out yourself.