<?php

/**
 * FormTranscript
 * Generic class for displaying transcripts of forms that are directly associated with a model.
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package form
 */
class FormTranscript extends FormDisplay {
	public function text($name, $length = 'medium', $maxlength = false, $required = false, $default = '', $type = 'text') {
		if ($default && !$this->values[$name])
			$value = $default;
		else
			$value = $this->get_escaped_value($name);

		printf(	'<span class="value">%s</span>', $value );
	}
	
	public function select($name, $options, $length = 'medium') {
		$id = $this->name_to_id($name);
		$current_value = $this->values[$name];
		$value = $options[$current_value];

		printf(	'<span class="value">%s</span>', $value );
	}
	public function textarea($name, $size = 'large') {
		$value = $this->get_escaped_value($name);

		printf(	'<p class="value">%s</p>', nl2br($value) );
	}
}