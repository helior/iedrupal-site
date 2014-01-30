<?php

namespace PhroznPlugin\Provider;

class EventsCollection extends \Phrozn\Provider\Base implements \Phrozn\Provider {
	public function get() {
		return array(
			'one' => array('name' => 'One', 'ordinal' => 'first'),
			'two' => array('name' => 'Two', 'ordinal' => 'second'),
			'three' => array('name' => 'Two', 'ordinal' => 'third'),
		);
	}
}
