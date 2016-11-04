<?php
/**
 * Author: Makhmudbek Odilov
 * email: mahmudbekstudio@mail.ru
 * Date: 05.11.13
 * Time: 10:45
 */

class Language {

	private static $lang = '';                        // current language code
	private static $language = NULL;                    // Language class handle
	private static $lang_list = array();                // loaded language translations
	private static $lang_loaded = array();              // language status loaded or not
	private static $lang_path = NULL;                   // languages file path
	private static $lang_space = array();               // language space for translation
	private static $enabled_languages = array();    // list of enabled languages
	private static $language_name = array();   // Names of enabled languages
	private static $flag_url = 'flags';

	const LANG_LOAD = 1;                                // return this value when language file load
	const LANG_LOADED = 1;                              // return this value when language file loaded
	const LANG_NOT_LOAD = 1;                            // return this value when language file not load or file not exist

	private static $error = '';                         // Language error

	/**
	 * Constructor Language
	 */
	private function __construct($config) {
		try {
			if (empty($config)) {
				throw new Exception('Config is empty');
			}
			self::setEnabledLanguages($config['enabled_languages']);
			if (empty(self::$enabled_languages)) {
				throw new Exception('list enabled languages is empty.');
			}
			self::setLang($config['language']);
			self::setLanguageName($config['language_name']);

		} catch (Exception $e) {
			self::$error = 'File: ' . $e->getFile() . '<br />Class: ' . __CLASS__ . '<br />Error Code: ' . $e->getCode() . '<br />Error Message: ' . $e->getMessage();
		}

		self::setPath(dirname(__FILE__) . '/languages');
	}

	/**
	 * set List of language names
	 *
	 * @param $lang_names
	 */
	public static function setLanguageName($lang_names) {
		self::$language_name = $lang_names;
	}

	/**
	 * set enabled languages
	 *
	 * @param $lang_list list of lang codes
	 * @throws Exception
	 */
	public static function setEnabledLanguages($lang_list) {
		if (empty($lang_list)) {
			throw new Exception('list enabled languages is empty.');
		}
		self::$enabled_languages = $lang_list;
		foreach (self::$enabled_languages as $lang_code) {
			self::$lang_loaded[$lang_code] = false;
		}
	}

	/**
	 * get language class error
	 *
	 * @return string
	 */
	public static function getError() {
		return self::$error;
	}

	/**
	 * Initializing Language
	 */
	public static function init($config = array('language' => 'en', 'enabled_languages' => array('en'), 'language_name' => array('English'))) {
		if (is_null(self::$language)) {
			self::$language = new self($config);
		}
	}

	/**
	 * set path for language files
	 *
	 * @param $path string language file path
	 */
	public static function setPath($path) {
		self::$lang_path = $path;
	}

	/**
	 * add new space to language
	 *
	 * @param $space space name
	 */
	public static function addSpace($space) {
		self::$lang_space[] = $space;
	}

	/**
	 * remove language space and translations
	 *
	 * @param $space
	 * @param bool $remove_translation
	 * @return bool
	 */
	public static function removeSpace($space, $remove_translation = false) {
		if (in_array($space, self::$lang_space)) {
			if ($remove_translation) {
				foreach (self::$enabled_languages as $lang_code) {
					unset(self::$lang_list[$lang_code . '_' . $space]);
					unset(self::$lang_loaded[$lang_code . '_' . $space]);
				}
			}
			foreach (self::$lang_space as $key => $val) {
				if ($val == $space) {
					unset(self::$lang_space[$key]);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @return string current language code
	 */
	public static function getLang() {
		return self::$lang;
	}

	/**
	 * set active language code
	 *
	 * @param $lang_code
	 */
	public static function setLang($lang_code) {
		self::$lang = $lang_code;
	}

	/**
	 * @param string $lang_code
	 * @return string exist language code
	 */
	private static function getLangCode($lang_code = '') {
		if ($lang_code != '' && isset(self::$lang_loaded[$lang_code])) {
			$lang = $lang_code;
		} else {
			$lang = self::$lang;
		}
		return $lang;
	}

	/**
	 * @param string $lang_code
	 * @return mixed Language name
	 */
	public static function getLangName($lang_code = '') {
		$lang = self::getLangCode($lang_code);
		return self::$language_name[$lang];
	}

	/**
	 * set language flag url
	 *
	 * @param $url
	 */
	public static function setFlagUrl($url) {
		self::$flag_url = $url;
	}

	/**
	 * @param string $lang_code
	 * @return string language flag url
	 */
	public static function getLangFlag($lang_code = '') {
		$lang = self::getLangCode($lang_code);
		return self::$flag_url . '/' . $lang . '.png';
	}

	/**
	 * check if language not loaded
	 *
	 * @param $lang_code
	 * @return int
	 */
	private static function checkLang($lang_code) {
		return self::checkLangSpace('', $lang_code);
	}

	/**
	 * check language for space
	 *
	 * @param $space
	 * @param $lang_code
	 * @return int
	 */
	private static function checkLangSpace($space, $lang_code) {
		$result = self::LANG_LOADED;
		$lang = self::getLangCode($lang_code);
		if (!isset(self::$lang_loaded[$lang . '_' . $space]) || !self::$lang_loaded[$lang . '_' . $space]) {
			self::loadSpace($space, $lang);
			$result = self::LANG_LOAD;
		}
		return $result;
	}

	/**
	 * load language from file
	 *
	 * @param string $lang_code
	 * @return int
	 */
	private static function load($lang_code = '') {
		return self::loadSpace('', $lang_code);
	}

	/**
	 * load language for space
	 *
	 * @param $space
	 * @param string $lang_code
	 * @return int
	 */
	private static function loadSpace($space, $lang_code = '') {
		$space = ($space != '') ? '_' . $space : '';
		$lang = self::getLangCode($lang_code) . $space;
		if (isset(self::$lang_loaded[$lang]) && self::$lang_loaded[$lang]) {
			return self::LANG_LOADED;
		}
		self::$lang_loaded[$lang] = true;
		$lang_path = self::$lang_path . '/' . $lang . '.php';
		if (file_exists($lang_path)) {
			self::$lang_list[$lang] = include($lang_path);
			return self::LANG_LOAD;
		}
		return self::LANG_NOT_LOAD;
	}

	/**
	 * check translation text in space
	 *
	 * @param $space
	 * @param $lang_code
	 * @param $text
	 * @return bool
	 */
	private static function issetSpaceLangItem($space, $lang_code, $text) {
		$space = ($space != '') ? '_' . $space : '';
		$lang = self::getLangCode($lang_code) . $space;
		return isset(self::$lang_list[$lang][$text]);
	}

	/**
	 * get translation text in space
	 *
	 * @param $space
	 * @param $lang_code
	 * @param $text
	 * @return mixed
	 */
	private static function getSpaceLangItem($space, $lang_code, $text) {
		$space_prefix = ($space != '') ? '_' . $space : '';
		$lang = self::getLangCode($lang_code) . $space_prefix;
		if (self::issetSpaceLangItem($space, $lang_code, $text)) {
			return self::$lang_list[$lang][$text];
		} else {
			return $text;
		}
	}

	/**
	 * check translation text in main space
	 *
	 * @param $lang_code
	 * @param $text
	 * @return bool
	 */
	private static function issetLangItem($lang_code, $text) {
		return self::issetSpaceLangItem('', $lang_code, $text);
	}

	/**
	 * get text translation text in main space
	 *
	 * @param $lang_code
	 * @param $text
	 * @return mixed
	 */
	private static function getLangItem($lang_code, $text) {
		return self::getSpaceLangItem('', $lang_code, $text);
	}

	/**
	 * return translation of text
	 *
	 * @param $text  text for translation
	 * @param string $lang_code translation language code
	 * @return mixed translated text
	 */
	public static function __($text, $lang_code = '') {
		$lang = self::getLangCode($lang_code);
		$lang_spaces = array_reverse(self::$lang_space);
		$lang_spaces[] = '';
		foreach ($lang_spaces as $space) {
			self::checkLangSpace($space, $lang);
			if (self::issetSpaceLangItem($space, $lang, $text)) {
				return self::getSpaceLangItem($space, $lang, $text);
			}
		}
		return $text;
	}

	/**
	 * echo translation text
	 *
	 * @param $text text for translation
	 * @param string $lang_code translation language code
	 */
	public static function _e($text, $lang_code = '') {
		echo self::__($text, $lang_code);
	}

	/**
	 * get translation text in space
	 *
	 * @param $text
	 * @param $space
	 * @param string $lang_code
	 * @return mixed
	 */
	public static function __Space($text, $space, $lang_code = '') {
		$lang = self::getLangCode($lang_code);
		self::checkLangSpace($space, $lang);
		if (self::issetSpaceLangItem($space, $lang, $text)) {
			return self::getSpaceLangItem($space, $lang, $text);
		}
		return $text;
	}

	/**
	 * echo translation text in space
	 *
	 * @param $text
	 * @param $space
	 * @param string $lang_code
	 */
	public static function _eSpace($text, $space, $lang_code = '') {
		echo self::__Space($text, $space, $lang_code);
	}

}

// Initializing Language
//Language::init();