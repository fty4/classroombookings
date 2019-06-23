<?php

$entities = array_keys($entity_options);
$entities = array_flip($entities);

$table = \Jupitern\Table\Table::instance();
$table->attr('table', 'class', 'table');
$table->setData($fields);

$table->column()
	->title(lang('field_field_title'))
	->value(function($field) {
		return anchor('custom_fields/update/' . $field->field_id, html_escape($field->title));
	})
	->attr('td', 'class', 'table-title-cell')
	->css('th', 'width', '33%')
	->add();

$table->column()
	->title(lang('field_field_entity'))
	->value(function($field) {
		return FieldHelper::entity_label($field);
	})
	->css('th', 'width', '33%')
	->add();

$table->column()
	->title(lang('field_field_type'))
	->value(function($field) {
		return FieldHelper::type_label($field);
	})
	->css('th', 'width', '33%')
	->add();

$table->attr('tr', 'data-tag', function($field) use ($entities) {
	$tag_num = '';
	if (array_key_exists($field->entity, $entities)) {
		$tag_num = $entities[$field->entity]+1;
	}
	return "tag-{$tag_num}";
});

$table->attr('tr', 'class', 'filter-item');

$content = $table->render(true);

if (count($fields) === 0) {

	$this->load->view('partials/empty', [
		'title' => lang('fields_none'),
		'description' => lang('fields_none_hint'),
		'icon' => 'code',
		'action' => anchor("custom_fields/add", lang('fields_action_add'), 'class="btn btn-primary"'),
	]);

	return;
}

?>


<div class="filter">

	<?php

	$filter_nav_items = [];
	$filter_inputs = [];

	$filter_inputs[] = form_radio([
		'id' => "tag-0",
		'class' => "filter-tag",
		'name' => "filter-radio",
		'hidden' => 'hidden',
		'checked' => true,
	]);

	$all_name = lang('all');
	$filter_nav_items[] = "<label class='chip' for='tag-0'>{$all_name}</label>";

	foreach ($entities as $entity => $tag_num) {
		$tag_num += 1;
		$label = lang("fields_entity_{$entity}");
		$filter_nav_items[] = "<label class='chip' for='tag-{$tag_num}'>{$label}</label>";
		$filter_inputs[] = form_radio([
			'id' => "tag-{$tag_num}",
			'class' => "filter-tag",
			'name' => "filter-radio",
			'hidden' => 'hidden',
		]);
	}

	echo implode("\n", $filter_inputs);
	echo "<div class='filter-nav'>" . implode("\n", $filter_nav_items) . "</div>";

	echo "<div class='filter-body filter-body-block'>";

	echo table_box([
		'title' => '',
		'table' => $content,
	]);

	echo "</div>";

	?>

</div>

