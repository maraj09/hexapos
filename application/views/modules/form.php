<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('modules/save/' . $module_id, array('id' => 'module_form', 'class' => 'form-horizontal')); ?>
<fieldset id="module_basic_info">


	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('module_id'), 'module_id', array('class' => 'control-label col-xs-3 required')); ?>
		<div class='col-xs-4'>
			<?php echo form_input(
				array(
					'name' => 'module_id',
					'id' => 'module_id',
					'class' => 'form-control input-sm',
					'value' => $giftcard_number
				)
			); ?>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('name_lang_key'), 'name_lang_key', array('class' => 'control-label col-xs-3 required')); ?>
		<div class='col-xs-8'>
			<?php echo form_input(
				array(
					'name' => 'name_lang_key',
					'id' => 'name_lang_key',
					'class' => 'form-control input-sm',
					'value' => $selected_person_name
				)
			); ?>
		</div>
	</div>


	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('desc_lang_key '), 'desc_lang_key ', array('class' => 'control-label col-xs-3 required')); ?>
		<div class='col-xs-4'>
			<?php echo form_input(
				array(
					'name' => 'desc_lang_key',
					'id' => 'desc_lang_key ',
					'class' => 'form-control input-sm',
					'value' => $giftcard_number
				)
			); ?>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('sort'), 'sort', array('class' => 'control-label col-xs-3')); ?>
		<div class='col-xs-4'>
			<?php echo form_input(
				array(
					'name' => 'sort',
					'id' => 'sort',
					'class' => 'form-control input-sm',
					'value' => $giftcard_number
				)
			); ?>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<?php echo form_label($this->lang->line('status'), 'status', array('class' => 'control-label col-xs-3')); ?>
		<div class='col-xs-4'>
			<?php echo form_input(
				array(
					'name' => 'status',
					'type' => 'checkbox',
					'id' => 'status',
					'class' => 'form-check-input',
					'value' => 1,
					'checked' => TRUE
				),
			); ?>
			<label class="form-check-label">
				Active
			</label>
		</div>
	</div>

</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
	//validation and submit handling
	$(document).ready(function() {
		// $("input[name='person_name']").change(function() {
		// 	!$(this).val() && $(this).val('');
		// });

		// var fill_value = function(event, ui) {
		// 	event.preventDefault();
		// 	$("input[name='person_id']").val(ui.item.value);
		// 	$("input[name='person_name']").val(ui.item.label);
		// };

		// $('#person_name').autocomplete({
		// 	source: "<?php echo site_url('customers/suggest'); ?>",
		// 	minChars: 0,
		// 	delay: 15,
		// 	cacheLength: 1,
		// 	appendTo: '.modal-content',
		// 	select: fill_value,
		// 	focus: fill_value
		// });

		$('#module_form').validate($.extend({
			submitHandler: function(form) {
				$(form).ajaxSubmit({
					success: function(response) {
						console.log(response);
						dialog_support.hide();
						table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						table_support.handle_submit("<?php echo site_url($controller_name); ?>", {
							message: errorThrown
						});
					},
					dataType: 'json'
				});
			},

			errorLabelContainer: '#error_message_box',

			rules: {
				module_id: {
					required: true,
				},
				name_lang_key: {
					required: true,
				},
				desc_lang_key: {
					required: true,
				},

			},
			messages: {
				module_id: {
					required: "<?php echo $this->lang->line('module_id_required'); ?>",
				},
				name_lang_key: {
					required: "<?php echo $this->lang->line('module_name_required'); ?>",
				},
				desc_lang_key: {
					required: "<?php echo $this->lang->line('module_desc_required'); ?>",
				}
			}
		}, form_support.error));
	});
</script>