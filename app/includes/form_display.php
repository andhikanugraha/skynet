<?php

/**
 * FormDisplay
 * Generic class for displaying forms that are directly associated with a model.
 *
 * How to use:
 * $form = new FormDisplay;
 * $form->associate($record);
 * The form will automatically be filled with values from $record
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package form
 */

class FormDisplay {
	/**
	 * Array of labels for form fields
	 */
	public $labels = array();
	
	/**
	 * Array of translations
	 */
	public static $translations = array();
	
	/**
	 * Array of provinces
	 */
	public static $address_states = array();

	public $raw_values = array();
	public $values = array();
	public $base = '';
	public $walker = array();

	public $associated_object;
	public $postdata = array();
	public $column_types = array();

	public function __construct($associated_object = null) {
		if (is_object($associated_object) && $associated_object instanceof HeliumRecord)
			$this->associate($associated_object);
	}

	/**
	 * Associate form with record
	 */
	public function associate(HeliumRecord $associated_object) {
		$this->associated_object = $associated_object;

		if ($associated_object->_is_vertically_partitioned)
			$this->column_types = array_merge($associated_object->_column_types, $associated_object->_vertical_partition_column_types);
		else
			$this->column_types = $associated_object->_column_types;

		$fields = array();
		foreach ($this->column_types as $col => $type) {
			if ($type == 'datetime') {
				// convert dates into arrays
				// this way, we can have fields that aren't stored as dates in MySQL,
				// as well as true datetime fields
				$datetime = $associated_object->$col;

				if ($datetime instanceof DateTime) {
					$dummy = array();
					$strings = array('year' => 'Y', 'month' => 'm', 'day' => 'd', 'hour' => 'H', 'minute' => 'i', 'second' => 's');
					foreach ($strings as $string => $f) {
						$dummy[$string] = $datetime->format($f);
					}
					$fields[$col] = $dummy;
				}
			}
			else
				$fields[$col] = $associated_object->$col;
		}

		$this->feed($fields);
	}

	/**
	 * Field value mapping functions
	 */

	public function feed($fields = array()) {
		if (!$fields)
			$fields = $_POST;

		$this->raw_values = $fields;

		// first level; no recursion.
		foreach ($fields as $field => $value) {
			if (!is_array($value))
				$this->values[$field] = $value;
			else {
				$this->base = $field;
				$this->nest($field, $value);
			}
		}
	}

	public function nest($field, $value) {
		if (is_array($value)) {
			if ($this->base != $field)
				$this->walker[] = $field;
			foreach ($value as $f => $v)
				$this->nest($f, $v);
			array_pop($this->walker);
		}
		else {
			$name = $this->base . $this->glue() . "[" .$field ."]";
			$this->values[$name] = $value;
		}
	}

	public function glue() {
		$glue = implode('][', $this->walker);
		if ($glue)
			return '[' . $glue . ']';
	}

	/**
	 * Form field display functions
	 */
	
	private function get_escaped_value($name) {
		return htmlspecialchars($this->values[$name]);
	}

	private function __($string = '') {
		if (self::$translations[$string])
			return self::$translations[$string];
		else
			return $string;
	}
	
	private function name_to_id($name) {
		return str_replace(array('.', '[', ']'), '-', $name);
	}

	public function label($name, $default_label = '', $classes = '') {
		if ($this->labels[$name])
			$label = $this->labels[$name];
		else {
			$label = $this->__($default_label);
			$this->labels[$name] = $label;
		}
		
		printf(	'<label for="%s" class="%s">%s</label>',
				$this->name_to_id($name),
				$classes,
				$label );
	}

	public function text($name, $length = 'medium', $maxlength = false, $required = false, $default = '', $type = 'text') {
		if ($default && !$this->values[$name])
			$value = $default;
		else
			$value = $this->get_escaped_value($name);

		printf(	'<input type="%s" name="%s" id="%s" class="%s" value="%s"',
				$type,
				$name,
				$this->name_to_id($name),
				$length,
				$value );

		if ($maxlength)
			echo ' maxlength="' . $maxlength . '"';

		if ($required)
			echo ' required';

		echo '>';
	}

	public function number($name, $length = 'medium', $maxlength = false, $required = false, $default = '') {
		$this->text($name, $length, $maxlength, $required, $default, 'number');
	}

	public function tel($name, $length = 'medium', $maxlength = false, $required = false, $default = '') {
		$this->text($name, $length, $maxlength, $required, $default, 'tel');
	}

	public function email($name, $length = 'medium', $maxlength = false, $required = false, $default = '') {
		$this->text($name, $length, $maxlength, $required, $default, 'email');
	}
	
	public function password($name, $length = 'medium', $maxlength = false, $required = false, $default = '') {
		$this->text($name, $length, $maxlength, $required, $default, 'password');
	}

	public function textarea($name, $size = 'large') {
		printf(	'<textarea name="%s" id="%s" class="%s">%s</textarea>',
				$name,
				$this->name_to_id($name),
				$size,
				$this->get_escaped_value($name) );
	}

	public function select($name, $options, $length = 'medium') {
		$id = $this->name_to_id($name);
		$current_value = $this->values[$name];
?>

<select name="<?php echo $name ?>" id="<?php echo $id ?>" class="<?php echo $length ?>">
<?php
	foreach ($options as $k => $v):
		$is_selected = ((!$current_value && $k === $current_value) || ($current_value && $k == $current_value));
		$selected = $is_selected ? ' selected' : '';
?>
	<option value="<?php echo $k; ?>"<?php echo $selected; ?>><?php echo $v; ?></option>
<?php endforeach; ?>
</select>

<?php
	}

	public function select_number($name, $bottom_limit, $upper_limit, $class = 'medium') {
		$numbers = array('' => ' ');

		if ($bottom_limit < $upper_limit) {
			for ($i = (int) $bottom_limit; $i <= (int) $upper_limit; $i++)
				$numbers[$i] = $i;
		}
		else {
			for ($i = (int)$bottom_limit; $i >= (int) $upper_limit; $i--)
				$numbers[$i] = $i;
		}
		if (is_string($bottom_limit)) {
			$length = strlen($bottom_limit);
			foreach ($numbers as $v) {
				$padded = str_pad($v, $length, '0', STR_PAD_LEFT);
				$numbers[$padded] = $padded;
			}
		}

		$this->select($name, $numbers, $class);
	}

	public function select_second($name) {
		$this->select_number($name, '00', 60, 'very-short date-s');
	}
	
	public function select_minute($name) {
		$this->select_number($name, '00', 31, 'very-short date-i');
	}
	
	public function select_hour($name) {
		$this->select_number($name, '00', 24, 'very-short date-h');
	}

	public function select_day($name) {
		$this->select_number($name, 0, 31, 'very-short date-d');
	}

	public function select_month($name) {
		$months = array(0 => $this->__('(Month)'),
						1 => $this->__('January'),
						2 => $this->__('February'),
						3 => $this->__('March'),
						4 => $this->__('April'),
						5 => $this->__('May'),
						6 => $this->__('June'),
						7 => $this->__('July'),
						8 => $this->__('August'),
						9 => $this->__('September'),
						10 => $this->__('October'),
						11 => $this->__('November'),
						12 => $this->__('December'));

		$this->select($name, $months, 'short date-m');
	}

	public function select_year($name, $start, $end) {
		$years = array(0 => $this->__('(Tahun)'));
		for ($i = $start; $i >= $end; $i--)
			$years[$i] = $i;

		$this->select($name, $years, 'very-short date-y');
	}

	// TODO: Support HTML5 dates
	public function date($name, $years_ago_start = 70, $years_ago_end = 0) {
		// Day
		$this->select_day($name . '[day]');
		
		// Month
		$this->select_month($name . '[month]');
		
		// Year
		$this_year = intval(date('Y'));
		$start = $this_year - $years_ago_start;
		$end = $this_year - $years_ago_end;
		$this->select_year($name . '[year]', $start, $end);
	}

	public function checkbox($name, $value = '1') {
		$checked = (bool) $this->values[$name];

		printf(	'<input type="checkbox" name="%s" id="%s" value="%s"%s><input type="hidden" name="_checkboxes[]" value="%1$s">',
				$name,
				$this->name_to_id($name),
				$value,
				$checked ? ' checked' : '' );
	}
	
	public function radio($name, $value) {
		$current_value = $this->values[$name];
		$checked = ($current_value == $value);

		printf(	'<input type="radio" name="%s" id="%s" value="%s"%s>',
				$name,
				$this->name_to_id($name),
				$value,
				$checked ? ' checked' : '' );
	}
	
	public function province($name, $length = 'medium', $placeholder = '') {
		$states = array('' => $placeholder);
		foreach (self::$address_states as $state) {
			$states[$state] = $state;
		}

		$this->select($name, $states, $length);
	}

	public function address($name, $kota = true, $provinsi = true, $kodepos = true, $telepon = true, $hp = true, $fax = true, $email = true) {
		?>
		<?php $this->textarea($name . '_address_street') ?>
		<?php if ($kota): ?>
		<br>
		<?php $this->label($name . '_address_city', 'Town', 'subpoint') ?>
		<?php $this->text($name . '_address_city', 'medium') ?>
		<?php
		endif;
		if ($provinsi):
		?>
		<br>
		<?php $this->label($name . '_address_province', 'State/Province', 'subpoint') ?>
		<?php $this->province($name . '_address_province', 'medium') ?>
		<?php
		endif;
		if ($kodepos):
		?>
		<br>
		<?php $this->label($name . '_address_postcode', 'Postcode', 'subpoint') ?>
		<?php $this->number($name . '_address_postcode', 'short', 5) ?>
		<?php
		endif;
		if ($telepon):
		?>
		<br>
		<?php $this->label($name . '_phone_areacode', 'Home Phone', 'subpoint') ?>
		( <?php $this->tel($name . '_phone_areacode', 'very-short', 4) ?> )
		<?php $this->tel($name . '_phone_number', 'short', 12) ?>
		<?php
		endif;
		if ($hp):
		?>
		<br>
		<?php $this->label($name . '_mobilephone', 'HP', 'subpoint') ?>
		<?php $this->tel($name . '_mobilephone', 'short', 12) ?>
		<?php
		endif;
		if ($fax):
		?>
		<br>
		<?php $this->label($name . '_fax_areacode', 'Fax', 'subpoint') ?>
		( <?php $this->tel($name . '_fax_areacode', 'very-short', 3) ?> )
		<?php $this->tel($name . '_fax_number', 'short', 8) ?>
		<?php
		endif;
		if ($email):
		?>
		<br>
		<?php $this->label($name . '_email', 'E-mail', 'subpoint') ?>
		<?php $this->email($name . '_email', 'medium') ?>
		<?php endif;
	}
}