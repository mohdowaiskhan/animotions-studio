<label class="option <?php echo esc_attr($params['attribute']); ?> <?php echo (empty($params['selected']) ? '' : 'checked'); ?>" title="<?php echo apply_filters($id . '_tooltip_title', '', $params); ?>" data-attribute="<?php echo esc_attr($params['attribute']); ?>" data-my="<?php echo esc_attr(apply_filters($id . '_tooltip_my', 'center bottom', $params)); ?>" data-at="<?php echo esc_attr(apply_filters($id . '_tooltip_at', 'center top+10', $params)); ?>" style="<?php echo esc_attr(apply_filters($id . '_label_style', '', $params)); ?>">
	<?php do_action($id . '_before_label', $params); ?>
	<div class="label">
		<input type="radio" name="<?php echo esc_attr($params['name']); ?>" data-attribute_name="attribute_<?php echo esc_attr(sanitize_title($params['attribute'])); ?>" value="<?php echo esc_attr($params['value']); ?>" <?php echo (empty($params['selected']) ? '' : 'checked'); ?>/>
		<?php do_action($id . '_label', $params); ?>
	</div>
	<?php do_action($id . '_after_label', $params); ?>
</label>
