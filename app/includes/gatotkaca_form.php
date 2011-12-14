<?php

class GatotkacaForm {
	public $raw_values = array();
	public $values = array();
	public $base = '';
	public $walker = array();

	public function glue() {
		$glue = implode('][', $this->walker);
		if ($glue)
			return '[' . $glue . ']';
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
}
