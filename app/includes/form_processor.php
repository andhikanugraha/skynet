<?php

/**
 * FormProcessor
 * Generic class for processing forms that are directly associated with a model.
 *
 * How to use:
 * $proc = new FormProcessor;
 * $proc->associate($record); // $record is the record to be updated
 * $proc->commit(); // saves the record using data from $_POST
 * $record->save();
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package form
 */

class FormProcessor {
	public $associated_object;
	public $postdata = array();
	public $column_types = array();
	public $uneditables = array('id');

	public function __construct($post = array()) {
		if (!$post || !is_array($post))
			$post = $_POST;

		foreach ($this->uneditables as $u)
			unset($post[$u]);

		$this->postdata = $post;
	}

	public function associate(HeliumRecord $associated_object) {
		$this->associated_object = $associated_object;

		if ($associated_object->_is_vertically_partitioned)
			$this->column_types = array_merge($associated_object->_column_types, $associated_object->_vertical_partition_column_types);
		else
			$this->column_types = $associated_object->_column_types;
	}

	public function add_uneditables() {
		$args = func_get_args();
		$this->uneditables = array_merge($this->uneditables, $args);
	}

	public function commit() {
		if (!$this->column_types)
			return false;

		$post = $this->postdata;

		foreach ($this->column_types as $col => $type) {
			$value = $post[$col];
			if (!in_array($col, $this->uneditables)) {
				if ($type == 'datetime') {
					// if it's a date then we have to treat the field as a date
					$existing = $this->associated_object->$col;
					if (!$existing)
						$this->associated_object->$col = $existing = new HeliumDateTime('0000-00-00 00:00:00');
					
					if (is_array($value)) { // Classic three-select form control
						$year = $month = $day = $hour = $minute = $second = 0;
						foreach ($value as $k => $v) {
							if (!is_numeric($v))
								unset($value[$k]);
							else
								$value[$k] = intval($v);
						}

						extract($value);
						$existing->setDate($year, $month, $day);
						$existing->setTime($hour, $minute, $second);
					}
					else { // (Possibly) HTML5
						$existing = new HeliumDateTime(trim($value));
					}
				}
				elseif (in_array($col, $this->associated_object->_auto_serialize))
					$this->associated_object->$col = $value;
				else {
					settype($value, $type);
					$this->associated_object->$col = trim($value);
				}
			}
		}
	}

}