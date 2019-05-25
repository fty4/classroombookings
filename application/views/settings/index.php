<?php

echo "<div class='columns columns-settings'>";

echo render_menu([
	'items' => $items,
	'item_class' => 'column col-sm-12 col-md-6 col-4',
	'item_template' => '<div class="{item_class} {item_active_class}">{link}</div>',
	'link_class' => 'tile tile-menu-item',
	'link_template' => '<a class="{link_class} {link_active_class}" href="{url}" {link_attrs}>
		<div class="tile-icon">{icon}</div>
		<div class="tile-content"><p class="tile-title">{label}</p><p class="tile-subtitle">{description}</p></div>
	</a>',
]);

echo "</div>";
