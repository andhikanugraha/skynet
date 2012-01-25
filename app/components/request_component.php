<?php

class RequestComponent extends HeliumComponent {
	public function init($controller) {
		$this->controller = $controller;

		$controller->_alias_method('get_relevant_chapter', array($this, 'get_relevant_chapter'));
	}

	/**
	 * Get the relevant chapter according to the current request
	 *
	 * This method looks for a parameter 'chapter_id' in the current request.
	 * If there is one, it checks whether the current user has access to said chapter.
	 * If he/she is, then this method returns the corresponding Chapter record.
	 * If he/she isn't, or there is no chapter_id paramter, and $fallback_to_own_chapter is true,
	 * then this method returns the user's own chapter.
	 *
	 * @param bool $fallback_to_own_chapter Fallback to currently logged in user's chapter (true) or not (false).
	 * @return Chapter|bool 
	 */
	public function get_relevant_chapter($fallback_to_own_chapter = false) {
		$params = $this->controller->params;
		$user = $this->controller->user;
		$chapter_id = $params['chapter_id'];

		if (!$user)
			return false;
		elseif ($chapter_id) {
			$chapter = Chapter::find($chapter_id);

			if ($chapter && $chapter instanceof Chapter && $user->has_access_to($chapter))
				return $chapter;
			elseif ($fallback_to_own_chapter)
				return $user->chapter;
			else
				return false;
		}	
		elseif ($fallback_to_own_chapter)
			return $user->chapter;
		else
			return false;
	}
}