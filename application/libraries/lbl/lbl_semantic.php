<?php

/**
 * LBL Semantic class
 *
 * LBL semantic functions
 *
 * @package 	LBL / Semantic
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_semantic extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_green('LBL-Library '.__CLASS__.' has been initialized');

		// We will auto-load the matching language file
		$language_file = strtolower(__CLASS__);
		$this->lang->load('lbl/' . $language_file, Language::pick());

	}

	/**
	 * Convert number-string to corresponding number
	 *
	 *  'one' become 1
	 *
	 *   Yeah, as simply as that.
	 *
	 * @access public
	 * @param string $str raw taped sentence from the user
	 * @return integer / bool
	 */
	public function _STR_TO_NUM($str='') {

		$str = $this->understandable($str);

		$arr = array(

			'one_bis' => 1,
			'one_ter' => 1,
			'one' => 1,
			'two' => 2,
			'three' => 3,
			'four' => 4,
			'five' => 5,
			'six' => 6,
			'seven' => 7,
			'eight' => 8,
			'nine' => 9,
			'ten' => 10

			);

		return $this->string_to_int($arr, $str);

	}

	// in 2 days
	public function _DAY_RELATIVE($str='') {

		// clean string (accents, unexcepted signs)
		$str = $this->understandable($str);

		// 'dans 3 jours' integration -> return 3
		if ($result = $this->check_interrogations($str, 'in_days'));
		elseif ($result = $this->check_interrogations($str, 'days'));

		// un -> 1
		$result = $this->_STR_TO_NUM($result);

		if ($result > 0) return $result;
		else return FALSE;

	}

	// in 2 weeks
	public function _WEEK_RELATIVE($str='') {

		// clean string (accents, unexcepted signs)
		$str = $this->understandable($str);

		// 'dans 3 jours' integration -> return 3
		if ($result = $this->check_interrogations($str, 'in_weeks'));
		elseif ($result = $this->check_interrogations($str, 'weeks'));

		// un -> 1
		$result = ($this->_STR_TO_NUM($result)*7);

		if ($result > 0) return $result;
		else return FALSE;

	}

	// in 2 months
	public function _MONTH_RELATIVE($str='') {

		// clean string (accents, unexcepted signs)
		$str = $this->understandable($str);

		// 'dans 3 jours' integration -> return 3
		if ($result = $this->check_interrogations($str, 'in_months'));
		elseif ($result = $this->check_interrogations($str, 'months'));

		// un -> 1
		$result = $this->_STR_TO_NUM($result); // 30.4368499

		// Auto-calculate + 1 month, thank you PHP
		$result = round(strtotime('+'.$result.' months') - time()) / (60 * 60 * 24);

		if ($result > 0) return $result;
		else return FALSE;

	}

	// in 2 years
	public function _YEAR_RELATIVE($str='') {

		// clean string (accents, unexcepted signs)
		$str = $this->understandable($str);

		// 'dans 3 jours' integration -> return 3
		if ($result = $this->check_interrogations($str, 'in_years'));
		elseif ($result = $this->check_interrogations($str, 'in_years_bis'));
		elseif ($result = $this->check_interrogations($str, 'years'));

		// un -> 1
		$result = $this->_STR_TO_NUM($result);

		// Auto-calculate + 1 year, thank you PHP
		$result = round(strtotime('+'.$result.' years') - time()) / (60 * 60 * 24);

		if ($result > 0) return $result;
		else return FALSE;

	}

	// Convert day-string to corresponding number (tomorrow, the day after tomorrow, next monday, monday)
	public function _NEARDAY_RELATIVE($str='') {

		$str = $this->understandable($str);

		$arr = array(

			'tomorrow' => 1,
			'the_day_after_tomorrow' => 2,

			);

		// If it's a special day
		if ($result = $this->string_to_int($arr, $str)) return $result;

		$arr = array(
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
			'sunday'
			);

		// If it's a relative day
		if ($result = $this->relative_string_to_int($arr, $str, 'from_today_to_this_day', time())) return $result;

		// 'next monday' intégration
		$arr2 = $this->replace_interrogations_from_array($arr, 'next_day');

		// If it's a relative day (alternative) - this much correspond to another equivalent array, such as above
		if ($result = $this->relative_string_to_int($arr, $str, 'from_today_to_this_day', time(), $arr2)) return $result;

		return FALSE;

	}


	// Convert time-string with unity to corresponding number (such as months/weeks/years)
	public function _DATE_RELATIVE($str='') {

		// NEXT SATURDAY / THE DAY AFTER TOMORROW / ...
		if ($result = $this->_NEARDAY_RELATIVE($str)) return $result;
		if ($result = $this->_DAY_RELATIVE($str)) return $result;
		if ($result = $this->_WEEK_RELATIVE($str)) return $result;
		if ($result = $this->_MONTH_RELATIVE($str)) return $result;
		if ($result = $this->_YEAR_RELATIVE($str)) return $result;

		return FALSE;

	}

	public function _SUBAREA_TO_BIGCITY($country, $subarea) {

		$country = $this->understandable($country);
		$subarea = $this->understandable($subarea);

		if ($country !== 'france') return FALSE; // Only available in France

		$map = array(
			'ain' => 'Bourg-en-Bresse',
			'aisne' => 'Laon',
			'allier' => 'Moulins',
			'alpes de haute provence' => 'Digne-les-Bains',
			'hautes alpes' => 'Gap',
			'alpes maritimes' => 'Nice',
			'ardeches' => 'Privas',
			'ardennes' => 'Charleville-Mézières',
			'ariege' => 'Foix',
			'aube' => 'Troyes',
			'aude' => 'Carcassone',
			'aveyron' => 'Rodez',
			'bouches du rhone' => 'Marseille',
			'calvados' => 'Caen',
			'cantal' => 'Aurillac',
			'charente' => 'Angoulême',
			'charente maritime' => 'La Rochelle',
			'cher' => 'Bourges',
			'correze' => 'Tulle',
			'corse du sud' => 'Ajaccio',
			'haute corse' => 'Bastia',
			'cote d\'or' => 'Dijon',
			'cotes d\'armor' => 'Saint-Brieuc',
			'creuse' => 'Guéret',
			'dordogne' => 'Périgueux',
			'doubs' => 'Besançon',
			'drome' => 'Valence',
			'eure' => 'Evreux',
			'eure et loir' => 'Chartres',
			'finistere' => 'Quimper',
			'gard' => 'Nîmes',
			'haute garonne' => 'Toulouse',
			'gers' => 'Auch',
			'gironde' => 'Bordeaux',
			'herault' => 'Montpellier',
			'ille et vilaine' => 'Rennes',
			'indre' => 'Châteauroux',
			'indre et loire' => 'Tours',
			'isere' => 'Grenoble',
			'jura' => 'Lons-le-Saunier',
			'landes' => 'Mont-de-Marsan',
			'loir et cher' => 'Blois',
			'loire' => 'Saint-Etienne',
			'haute loire' => 'Le Puy-en-Velay',
			'loire atlantique' => 'Nantes',
			'loiret' => 'Orléans',
			'lot' => 'Cahors',
			'lot et garonne' => 'Agen',
			'lozere' => 'Mende',
			'maine et loire' => 'Angers',
			'manche' => 'Saint-Lô',
			'marne' => 'Châlons-en-Champagne',
			'haute marne' => 'Chaumont',
			'mayenne' => 'Laval',
			'meurthe et moselle' => 'Nancy',
			'meuse' => 'Bar-le-Duc',
			'morbihan' => 'Vannes',
			'moselle' => 'Metz',
			'nievre' => 'Nevers',
			'nord' => 'Lille',
			'oise' => 'Beauvais',
			'orne' => 'Alençon',
			'pas de calais' => 'Arras',
			'puy de dome' => 'Clermont-Ferrand',
			'pyrenees atlantiques' => 'Pau',
			'hautes pyrenees' => 'Tarbes',
			'pyrenees orientales' => 'Perpignan',
			'bas rhin' => 'Colmar',
			'rhone' => 'Lyon',
			'haute saone' => 'Vesoul',
			'saone et loire' => 'Macon',
			'sarthe' => 'Le Mans',
			'savoie' => 'Chambéry',
			'haute savoie' => 'Annecy',
			'paris' => 'Paris',
			'seine maritime' => 'Rouen',
			'seine et marne' => 'Melun',
			'yvelines' => 'Versailles',
			'deux sevres' => 'Niort',
			'somme' => 'Amiens',
			'tarn' => 'Albi',
			'tarn et garonne' => 'Montauban',
			'var' => 'Toulon',
			'vaucluse' => 'Avignon',
			'vendee' => 'La Roche-sur-Yon',
			'vienne' => 'Poitiers',
			'haute vienne' => 'Limoges',
			'vosges' => 'Epinal',
			'yonne' => 'Auxerre',
			'territoire de belfort' => 'Belfort',
			'essonne' => 'Evry',
			'hauts de seine' => 'Nanterre',
			'seine saint-denis' => 'Bobigny',
			'val de marne' => 'Créteil',
			'val d\'oise' => 'Pontoise',
			'guadeloupe' => 'Basse-Terre',
			'martinique' => 'Fort-de-France',
			'guyane' => 'Cayenne',
			'la reunion' => 'Saint-Denis',
			'mayotte' => 'Mamouzdou'
			);

		if (isset($map[$subarea])) return $map[$subarea];
		return 'Unknown'; // Thanks to Google API, you did a great job with your billions.


	}

	public function _LANGUAGE_TO_SIGN($language) {

		// Protect data entry
		$language = $this->understandable($language);

		if (!isset($language{3})) return $language; // If it's fr, en, etc.

		// Function cache (if a cache is there, we return already our system)
		if ($cache = $this->get_cache(__METHOD__, $language)) return $cache;

		$code = array(

			$this->lang->line('english_language') => 'en',
			$this->lang->line('afrikaans_language') => 'af',
			$this->lang->line('albanian_language') => 'sq',
			$this->lang->line('arabic_language') => 'ar',
			$this->lang->line('armenian_language') => 'hy',
			$this->lang->line('azerbaijani_language') => 'az',
			$this->lang->line('basque_language') => 'eu',
			$this->lang->line('belarusian_language') => 'be',
			$this->lang->line('bengali_language') => 'bn',
			$this->lang->line('bulgarian_language') => 'bg',
			$this->lang->line('catalan_language') => 'ca',
			$this->lang->line('chinese_language') => 'zh-CN',
			$this->lang->line('croatian_language') => 'hr',
			$this->lang->line('czech_language') => 'cs',
			$this->lang->line('danish_language') => 'da',
			$this->lang->line('dutch_language') => 'nl',
			$this->lang->line('esperanto_language') => 'eo',
			$this->lang->line('estonian_language') => 'et',
			$this->lang->line('filipino_language') => 'tl',
			$this->lang->line('finnish_language') => 'fi',
			$this->lang->line('french_language') => 'fr',
			$this->lang->line('galician_language') => 'gl',
			$this->lang->line('georgian_language') => 'ka',
			$this->lang->line('german_language') => 'de',
			$this->lang->line('greek_language') => 'el',
			$this->lang->line('gujarati_language') => 'gu',
			$this->lang->line('haitian_creole_language') => 'ht',
			$this->lang->line('hebrew_language') => 'iw',
			$this->lang->line('hindi_language') => 'hi',
			$this->lang->line('hungarian_language') => 'hu',
			$this->lang->line('icelandic_language') => 'is',
			$this->lang->line('indonesian_language') => 'id',
			$this->lang->line('irish_language') => 'ga',
			$this->lang->line('italian_language') => 'it',
			$this->lang->line('japanese_language') => 'ja',
			$this->lang->line('kannada_language') => 'kn',
			$this->lang->line('korean_language') => 'ko',
			$this->lang->line('lao_language') => 'lo',
			$this->lang->line('latin_language') => 'la',
			$this->lang->line('latvian_language') => 'lv',
			$this->lang->line('lithuanian_language') => 'lt',
			$this->lang->line('macedonian_language') => 'mk',
			$this->lang->line('malay_language') => 'ms',
			$this->lang->line('maltese_language') => 'mt',
			$this->lang->line('norwegian_language') => 'no',
			$this->lang->line('persian_language') => 'fa',
			$this->lang->line('polish_language') => 'pl',
			$this->lang->line('portuguese_language') => 'pt',
			$this->lang->line('romanian_language') => 'ro',
			$this->lang->line('russian_language') => 'ru',
			$this->lang->line('serbian_language') => 'sr',
			$this->lang->line('slovak_language') => 'sk',
			$this->lang->line('slovenian_language') => 'sl',
			$this->lang->line('spanish_language') => 'es',
			$this->lang->line('swahili_language') => 'sw',
			$this->lang->line('swedish_language') => 'sv',
			$this->lang->line('tamil_language') => 'ta',
			$this->lang->line('telugu_language') => 'te',
			$this->lang->line('thai_language') => 'th',
			$this->lang->line('turkish_language') => 'tr',
			$this->lang->line('ukrainian_language') => 'uk',
			$this->lang->line('urdu_language') => 'ur',
			$this->lang->line('vietnamese_language') => 'vi',
			$this->lang->line('welsh_language') => 'cy',
			$this->lang->line('yiddish_language') => 'yi'

			);

		// We check "the ?" and shits
		if ($result = $this->check_interrogations($language, 'language_the'));
		elseif ($result = $this->check_interrogations($language, 'language_the_bis'));
		else ($result = $language);

		// 'e' exception manager (that's shit and i know it.)
		$alternative_result = substr($result, 0, -1);
		if (isset($code[$alternative_result])) $result = $alternative_result;

		if (isset($code[$result])) return $this->set_cache(__METHOD__, $language, $code[$result]); // Cache
		else return FALSE;

	}

	/**
	 * Convert semantic strings to real integer
	 *
	 *  It matchs an array with a language file and convert the word to a rational number
	 *
	 *  Be careful : this method doesn't understand strings, it's part of a system.
	 *
	 *  You put an array such as array('one' => 1) and it tries
	 *  to find the 'one' which can be 'un' or 'une' within the language file
	 *  If it matchs, it returns the number from the array
	 * 
	 *
	 * @access private
	 * @param array the $array to fetch containing all the correct couples string label / integer
	 * @param string $str the cleaned taped string from the user
	 * @return mixed (bool / integer)
	 */
	protected function string_to_int($array, $str) {

		$is_str_a_number = (int) $str;
		if ($is_str_a_number > 0) return $str;

		foreach ($array as $label => $row) {

			if ($str === $this->lang->line('phoenix_'.$label)) return (int) $row;

		}

		return FALSE;

	}

	/**
	 *  Understand string and convert it into a rational number depending on relative function
	 *
	 *  Similar to string_to_int() with a relative system inside
	 *  You put an array such as array('monday', 'thursday') and the system check each element
	 *  Using another dynamic functon trying to convert it into an integer
	 *
	 * @access private
	 * @param array the $array to fetch containing only the labels
	 * @param string $str the cleaned taped string from the user
	 * @param $function the genius function to call (it will check the string content)
	 * @param $arg1 an optional argument which will be used for the called genius function
	 * @param $array_match generally used after the '?' were replaced to get the result quicker
	 * @return mixed (bool / integer)
	 */
	protected function relative_string_to_int($array, $str, $function, $arg1='', $array_match=FALSE) {


		if ($array_match) {

			$int = 0;

			foreach ($array_match as $row) {

				if ($str === $row) return (int) $this->$function($arg1, $array[$int]);
				++$int;

			}

		} else {

			foreach ($array as $row) {

				if ($str === $this->lang->line('phoenix_'.$row)) return (int) $this->$function($arg1, $row);

			}

		}

		return FALSE;

	}

	/**
	 *  Interrogation system replacement
	 *
	 *  It uses each element from an array and replace the '?' successively
	 *  Be careful : it uses language files, the arrays and strings are only labels to call the real string
	 *
	 * @access private
	 * @param array the $array to fetch containing the language file string equivalent to check
	 * @param string $str the language file string corresponding to the sentence which (may) replace the '?'
	 * @return array containing replaced strings
	 */
	protected function replace_interrogations_from_array($array, $str) {

		$final_array = array();

		$this->lang->load('lbl/lbl_semantic', Language::pick());

		foreach ($array as $row) {

			if ($this->lang->line('phoenix_'.$row)) $final_array[] = str_replace('?', $this->lang->line('phoenix_'.$row), $this->lang->line('phoenix_'.$str));

		}

		return $final_array;

	}

	/**
	 *  Analyze a day and place it within the real time
	 *
	 *  (next) 'monday' will become a number depending on $actual_time
	 *
	 * @access private
	 * @param integer $actual_time a UNIX timestamp
	 * @param string $this_day is a week day (e.g. 'monday')
	 * @return integer corresponding to the difference between today and "this day" in days
	 */
	protected static function from_today_to_this_day($actual_time, $this_day) {

		//$one_day = 60*60*24;
		$today_sign = (int) date('N', $actual_time); // Numeric day representation (from 1 to 7)

		$monday = 1;
		$tuesday = 2;
		$wednesday = 3;
		$thursday = 4;
		$friday = 5;
		$saturday = 6;
		$sunday = 7;

		$day_code = strtolower($this_day);
		$day_code = ${$day_code}; // Numeric identity

		if ($today_sign === 7) $algo = $day_code; // In one week
		elseif ($today_sign === 6) $algo = $day_code+1;
		elseif ($today_sign === 5) $algo = $day_code+2;
		elseif ($today_sign === 4) $algo = $day_code+3;
		elseif ($today_sign === 3) $algo = $day_code+4;
		elseif ($today_sign === 2) $algo = $day_code+5;
		elseif ($today_sign === 1) $algo = $day_code+6;

		if ($algo > 7) $algo = $algo - 7;

		return $algo; //time() + ($one_day * $algo);

	}

}