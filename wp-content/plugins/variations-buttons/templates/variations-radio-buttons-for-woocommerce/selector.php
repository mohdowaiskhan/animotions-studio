<div class="<?php echo esc_attr($attribute); ?> <?php echo esc_attr($selectorStyle); ?> selector" data-attribute="<?php echo esc_attr($attribute); ?>">
<?php 

foreach ($options as $option) {
	$params = array_replace_recursive(array(
		'attribute' => $attribute,
		'name' => $name,
		'label' => '', 
		'description' => '', 
		'value' => '', 
		'selected' => false, 
		'color' => '', 
		'imageId' => null,
		'product' => $product
	), $option);

	wc_get_template($id . '/option.php', array('id' => $id, 'params' => $params));
}

?>
</div>