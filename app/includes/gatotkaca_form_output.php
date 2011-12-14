<?php

class GatotkacaFormOutput extends GatotkacaForm {

	private function value($name) {
		return htmlspecialchars($this->values[$name]);
	}

	public function text($name, $length = 'medium', $maxlength = false, $required = false, $type = 'text') {
		$id = str_replace(array('.', '[', ']'), '-', $name);
		echo '<input type="' . $type . '" name="' . $name . '" id="' . $id . '" class="' . $length . '" value="' . $this->value($name) . '"';
		if ($maxlength)
			echo ' maxlength="' . $maxlength . '"';
		if ($required)
			echo ' required';

		echo '>';
	}

	public function number($name, $length = 'medium', $maxlength = false, $required = false) {
		// $this->text($name, $length, $maxlength, $required, 'number');
		$this->text($name, $length, $maxlength, $required, 'text');
	}
	
	public function tel($name, $length = 'medium', $maxlength = false, $required = false) {
		$this->text($name, $length, $maxlength, $required, 'tel');
	}

	public function email($name, $length = 'medium', $maxlength = false, $required = false) {
		$this->text($name, $length, $maxlength, $required, 'email');
	}

	public function textarea($name, $size = 'large') {
		echo '<textarea class="' . $size . '" name="' . $name . '">' . $this->value($name) . '</textarea>';
	}

	public function select($name, $options, $length = 'medium') {
		$value = $this->values[$name];
		?>
		<select name="<?php echo $name ?>" class="<?php echo $length; ?>">
			<?php
			foreach ($options as $k => $v):
				$selected = ($k == $value) ? ' selected' : '';
			?>
			<option value="<?php echo $k; ?>"<?php echo $selected; ?>><?php echo $v; ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function select_month($name) {
		$months = array(0 => '(Bulan)', 1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
						7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');

		$this->select($name, $months, 'short date-m');
	}

	public function select_year($name, $start, $end) {
		$years = array(0 => '(Tahun)');
		for ($i = $start; $i >= $end; $i--)
			$years[$i] = $i;

		$this->select($name, $years, 'very-short date-y');
	}

	public function date($name, $full = false) {
		?>
		<?php
			$days = array(0 => ' ');
			for ($i = 1; $i <= 31; $i++)
				$days[$i] = $i;
			$this->select($name . '[day]', $days, 'very-short date-d');
		?>
		<?php
			$this->select_month($name . '[month]');
		?>
		<?php
			$years = array();
			$this_year = intval(date('Y'));
			if ($full)
				list($s, $e) = array(2011, 1950);
			else
				list($s, $e) = array($this_year - 14, $this_year - 17);

			$this->select_year($name . '[year]', $s, $e);
		?>
		<?php
	}
	
	public function checkbox($name, $value) {
		$values = $this->values[$name];
		if (!$values)
			$checked = false;
		elseif ( (is_array($values) && in_array($value, $values)) || $values == $value )
			$checked = true;
		else
			$checked = false;

		echo '<input type="checkbox" name="' . $name . '" value="' . $value . '"';
		if ($checked)
			echo ' checked';
		echo '>';
		echo '<input type="hidden" name="checkboxes[]" value="' . $name . '">';
	}

	public function address($name, $kota = true, $provinsi = true, $kodepos = true, $telepon = true, $hp = true, $fax = true, $email = true) {
		?>
		<?php $this->textarea($name . '_address_street') ?>
		<?php if ($kota): ?>
		<br>
		<label for="<?php echo $name ?>-kota" class="subpoint">Kota</label>
		<?php $this->text($name . '_address_city', 'medium') ?>
		<?php
		endif;
		if ($provinsi):
		?>
		<br>
		<label for="<?php echo $name ?>-provinsi" class="subpoint">Provinsi</label>
		<?php $this->text($name . '_address_province', 'medium') ?>
		<?php
		endif;
		if ($kodepos):
		?>
		<br>
		<label for="<?php echo $name ?>-kode_pos" class="subpoint">Kode Pos</label>
		<?php $this->number($name . '_address_postcode', 'short', 5) ?>
		<?php
		endif;
		if ($telepon):
		?>
		<br>
		<label for="<?php echo $name ?>-telepon-kode_area" class="subpoint">Telepon</label>
		( <?php $this->tel($name . '_phone_areacode', 'very-short', 4) ?> )
		<?php $this->tel($name . '_phone_number', 'short', 12) ?>
		<?php
		endif;
		if ($hp):
		?>
		<br>
		<label for="<?php echo $name ?>-hp" class="subpoint">HP</label>
		<?php $this->tel($name . '_mobilephone', 'short', 12) ?>
		<?php
		endif;
		if ($fax):
		?>
		<br>
		<label for="<?php echo $name ?>-fax" class="subpoint">Fax</label>
		( <?php $this->tel($name . '_fax_areacode', 'very-short', 3) ?> )
		<?php $this->tel($name . '_fax_number', 'short', 8) ?>
		<?php
		endif;
		if ($email):
		?>
		<br>
		<label for="<?php echo $name ?>-email" class="subpoint">E-mail</label>
		<?php $this->email($name . '_email', 'medium') ?>
		<?php endif;
	}
}