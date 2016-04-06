<?php

/**
 * Phoenix class
 *
 * The LBL-Libraries container, if a function doesn't fit anywhere in /lbl/ or we need to test it
 * This is the right place to be.
 * 
 * It also save every global variable linked with the LBL core, such as search_id_creator
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Phoenix extends Genius {

	public
	$search_id_creator = 0;

	public function __construct() {

		parent::__Construct();

		Devlog::add('Linkbreakers Core (v'.LINKBREAKERS_VERSION.') has been initialized');

	}

	/*public function _INDEED($key='6977853396406972', $location='bordeaux', $research='femme de ménage', $country='fr') {

		// We encode our research
		$research = urlencode($research);

		// Let's get it
		if (!$raw_result = @file_get_contents('http://api.indeed.com/ads/apisearch?publisher='.$key.'&q='.$research.'&l='.$location.'&co='.$country.'&v=2&format=json')) return FALSE;

		// We convert the datas to an understandable format
		$indeed = json_decode($raw_result, TRUE);

		if ($indeed['totalResults'] <= 0) return FALSE;

		$results = $indeed['results'];

		/**
		 *
		 * Ad title : $results[X]['jobtitle'] (e.g. 'Super femme de ménage')
		 * Company : company (e.g. Pôle Emploi)
		 * -
		 * Region : formattedLocation (e.g. Aquitaine)
		 * Departement : formattedLocationFull (e.g. Gironde)
		 * City : city (e.g. Bordeaux)
		 * -
		 * Website source : source (e.g. www.pole-emploi.fr)
		 * Date : date (e.g. Thu, 28 Nov 2013 07:15:58 GMT)
		 * Date relative : formattedRelativeTime (e.g. 'Il y a 3 heures')
		 * 
		 */
		/*
		return $results[0]['jobtitle'];

	}*/

	/////////////////////////////////
	// SERVER TREATMENTS FUNCTIONS //
	/////////////////////////////////
	//
/*
	public function _BENCH() {

		$this->benchmark = new CI_Benchmark();

		$french = 'french';
		$english = 'english';

		$ma_variable = 'test';

		$this->benchmark->mark('start');

		$int = 0;
		while ($int <= 100000) {

			// NOTHING

			++$int;
		}

		$this->benchmark->mark('middle');

		$int2 = 0;
		while ($int2 <= 100000) {

			// NOTHING 2

			++$int2;

		}

		$this->benchmark->mark('end');

		echo 'Try 1 : ' . $this->benchmark->elapsed_time('start', 'middle') . '<br />';
		echo 'Try 2 : ' . $this->benchmark->elapsed_time('middle', 'end') . '<br />';
		die();

	}
	*/

}
