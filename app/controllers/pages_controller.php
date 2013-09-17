<?php

class PagesController extends BaseController {

	protected function _home() {
		$this->page_title = 'Home';

		$this->render("pages/index");
	}

	protected function _about_us() {
		$this->page_title = 'About us';

		$this->view_assign(array(
			'param1' => 'value1', 
			'param2' => array('param2-value1' => 'value1')
		));

		$this->render("pages/about_us");
	}
}

?>