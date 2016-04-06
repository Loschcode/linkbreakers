<?php

/**
 * LBL Geolocalisation class
 *
 * LBL geolocalisation functions
 *
 * @package 	LBL / Geolocalisation
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_geolocalisation extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	public function _GEO($option='COUNTRY', $ip='') {

		if (empty($ip)) {

			$this->lbl_load('server_treatment');
			$ip = $this->lbl_server_treatment->_IP();

		}

		if (!function_exists('geoip_load_shared_mem')) {

			include("application/libraries/misc/geoloc/geoip.inc");
			include("application/libraries/misc/geoloc/geoipcity.inc");

		}

		$gi = geoip_open("assets/geo/GeoLiteCity.dat", GEOIP_STANDARD);
		$arr = geoip_record_by_addr($gi, $ip);
		geoip_close($gi);

		if ($option === 'COUNTRY') return utf8_encode($arr->country_name);
		if ($option === 'CITY') return utf8_encode($arr->city);

		if ($option === 'AREA') {

			include("geoloc/geoipregionvars.php");
			return utf8_encode($GEOIP_REGION_NAME[$arr->country_code][$arr->region]);

		}

		if (($option === 'SUBAREA') || ($option === 'BIGCITY')) {

			$latitude = $arr->latitude;
			$longitude = $arr->longitude;

			//$path = 'http://maps.google.com/maps/geo?output=json&q='.$latitude.','.$longitude;
			$path = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=true'; // Update 09/04/2013

			// AIzaSyBPR1ZnaTBjlkqAz1Xt5INtGzeQdrDHIc8

			if ($fgets2 = $this->get_cache(__METHOD__, $path));
			else $fgets2 = $this->set_cache(__METHOD__, $path, file_get_contents($path));

			$json_result = json_decode($fgets2, TRUE);

			/*
			
			$json_result['results'][0]['address_components'][4]['long_name'] = Aquitaine
			$json_result['results'][0]['address_components'][3]['long_name'] = Gironde
			$json_result['results'][0]['address_components'][2]['long_name'] = Ville

			*/

			if (isset($json_result['results'][0]['address_components'][3]['long_name'])) $subarea = $json_result['results'][0]['address_components'][3]['long_name'];
			else $subarea = '';

			if ($option === 'SUBAREA') return $subarea;
			if ($option === 'BIGCITY') {

				$this->lbl_load('semantic');
				return $this->lbl_semantic->_SUBAREA_TO_BIGCITY($arr->country_name, $subarea);

			}

		}

	}

}