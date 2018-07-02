<?php

namespace core\util;

use core\AppConfig;

class i18n {

	static public $intl_i18n;

	static function init()
	{
		//definindo constantes
		define('LANGS_DIR', AppConfig::getDirCore().'i18n'.DIRECTORY_SEPARATOR );
		define('DEFAULT_LANG', "pt_BR");
		define('DEFAULT_SECTION', "geral");

		// Listar todos os idiomas (arquivos .ini) disponíveis
		$langs = glob(LANGS_DIR . "*.ini");

		//TODO: tratar exception
		if (!$langs) {
			trigger_error("Error loading languages", E_USER_ERROR);
		}

		//carrega o $langs
		array_walk(
			$langs,
			create_function('&$l', '$l = basename($l, ".ini");')
		);

		$lang = "";
		// Verificar se um idioma foi definido
		if (isset($_REQUEST['lang'])) {
			// Pela seleção do usuário
			$lang = $_REQUEST['lang'];
		} else {
			// Pelo idioma do navegador
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
                $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}

		// Verificar se o idioma informado está disponível, caso contrário, usa default
		if (!in_array($lang, $langs)) {
			$lang = DEFAULT_LANG;
		}
		i18n::$intl_i18n = parse_ini_file(LANGS_DIR . $lang . ".ini", true);
	}

	static function write($key, $section = null) {
		return $section ? i18n::$intl_i18n[$section][$key] : i18n::$intl_i18n[DEFAULT_SECTION][$key];
	}

	static function writeParams($key, $section = null, $params = array()) {

		$aux = $section ? i18n::$intl_i18n[$section][$key] : i18n::$intl_i18n[DEFAULT_SECTION][$key];

		foreach ($params as $item) {
			$aux = preg_replace('/'.AppConfig::$I18N_REPLACE.'/', $item, $aux, 1);
		}

		return $aux;
	}

}