<?php

/**
 * Mustache (API) class
 *
 * Get every details about functions and strong-types
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Mustache {

	protected
	$error = array('error' => 'match not found');

	public function __construct() {

	}

	/**
 	*
 	* Get something from the API
 	*
 	* @param string $type function/strongtype
 	* @param string $focus the name of a peculiar function/strongtype
 	* @return mixed array
 	*
 	*/
	public function array_global($type=NULL, $focus=NULL) {

		//////////////////////
		//// STRONG TYPES ////
		//////////////////////

		///////////////////////
		//// SEMANTIC SLOW ////
		///////////////////////

		// CALC
		$global['strongtype']['calc']['short_description'] = 'Calcul mathématique';
		$global['strongtype']['calc']['genre'] = 'semantic (slow)';
		$global['strongtype']['calc']['format'] = '[calc]';
		$global['strongtype']['calc']['example'][] = 'https://gist.github.com/4380404';

		// LANGUAGE
		$global['strongtype']['language']['short_description'] = 'Langage existant';
		$global['strongtype']['language']['genre'] = 'semantic (slow)';
		$global['strongtype']['language']['format'] = '[language]';
		$global['strongtype']['language']['example'][] = 'https://gist.github.com/4379467';

		// DATE_RELATIVE
		$global['strongtype']['date_relative']['short_description'] = 'Date relative';
		$global['strongtype']['date_relative']['genre'] = 'semantic (slow)';
		$global['strongtype']['date_relative']['format'] = '[date_relative]';
		$global['strongtype']['date_relative']['example'][] = 'https://gist.github.com/4379446';

		///////////////////////
		//// SEMANTIC FAST ////
		///////////////////////

		// DOMAIN
		$global['strongtype']['domain']['short_description'] = 'Nom de domaine valide';
		$global['strongtype']['domain']['genre'] = 'semantic (fast)';
		$global['strongtype']['domain']['format'] = '[domain]';
		$global['strongtype']['domain']['example'][] = 'https://gist.github.com/4379417';

		// URL
		$global['strongtype']['url']['short_description'] = 'URL valide (protocole compris)';
		$global['strongtype']['url']['genre'] = 'semantic (fast)';
		$global['strongtype']['url']['format'] = '[url]';
		$global['strongtype']['url']['example'][] = 'https://gist.github.com/4379386';

		// IP
		$global['strongtype']['ip']['short_description'] = 'IP(v4) valide';
		$global['strongtype']['ip']['genre'] = 'semantic (fast)';
		$global['strongtype']['ip']['format'] = '[ip]';
		$global['strongtype']['ip']['example'][] = 'https://gist.github.com/4379330';

		///////////////////
		//// WORD-SIZE ////
		///////////////////

		// EXACT_WORD
		$global['strongtype']['exact_word']['short_description'] = 'Respect d\'un certain nombre de mots';
		$global['strongtype']['exact_word']['genre'] = 'word-size';
		$global['strongtype']['exact_word']['format'] = '[exact_word:0]';
		$global['strongtype']['exact_word']['example'][] = 'https://gist.github.com/4379239';

		// MAX_WORD
		$global['strongtype']['max_word']['short_description'] = 'Nombre de mots maximum prédéfini';
		$global['strongtype']['max_word']['genre'] = 'word-size';
		$global['strongtype']['max_word']['format'] = '[max_word:0]';
		$global['strongtype']['max_word']['example'][] = 'https://gist.github.com/4379230';

		// MIN_WORD
		$global['strongtype']['min_word']['short_description'] = 'Nombre de mots minimum prédéfini';
		$global['strongtype']['min_word']['genre'] = 'word-size';
		$global['strongtype']['min_word']['format'] = '[min_word:0]';
		$global['strongtype']['min_word']['example'][] = 'https://gist.github.com/4379219';

		// NOT_WORD
		$global['strongtype']['not_word']['short_description'] = 'Plusieurs mots (syntaxiquement parlant)';
		$global['strongtype']['not_word']['genre'] = 'word-size';
		$global['strongtype']['not_word']['format'] = '[not_word]';
		$global['strongtype']['not_word']['example'][] = 'https://gist.github.com/4379100';

		// WORD
		$global['strongtype']['word']['short_description'] = 'Un mot (syntaxiquement parlant)';
		$global['strongtype']['word']['genre'] = 'word-size';
		$global['strongtype']['word']['format'] = '[word]';
		$global['strongtype']['word']['example'][] = 'https://gist.github.com/4379084';

		///////////////////
		//// PURE-SIZE ////
		///////////////////

		// EXACT_SIZE
		$global['strongtype']['exact_size']['short_description'] = 'Respect d\'un certain nombre de caractères';
		$global['strongtype']['exact_size']['genre'] = 'pure-size';
		$global['strongtype']['exact_size']['format'] = '[exact_size:0]';
		$global['strongtype']['exact_size']['example'][] = 'https://gist.github.com/4379034';

		// MIN_SIZE
		$global['strongtype']['min_size']['short_description'] = 'Nombre de caractères minimum prédéfini';
		$global['strongtype']['min_size']['genre'] = 'pure-size';
		$global['strongtype']['min_size']['format'] = '[min_size:0]';
		$global['strongtype']['min_size']['example'][] = 'https://gist.github.com/4378927';

		// MAX_SIZE
		$global['strongtype']['max_size']['short_description'] = 'Nombre de caractère maximum prédéfini';
		$global['strongtype']['max_size']['genre'] = 'pure-size';
		$global['strongtype']['max_size']['format'] = '[max_size:0]';
		$global['strongtype']['max_size']['example'][] = 'https://gist.github.com/4378899';

		///////////////////
		//// DATA-TYPE ////
		///////////////////

		// NOT_NUM
		$global['strongtype']['not_num']['short_description'] = 'Entrée non-numérique';
		$global['strongtype']['not_num']['genre'] = 'data-type';
		$global['strongtype']['not_num']['format'] = '[not_num]';
		$global['strongtype']['not_num']['example'][] = 'https://gist.github.com/4378815';

		// NUM
		$global['strongtype']['num']['short_description'] = 'Entrée numérique';
		$global['strongtype']['num']['genre'] = 'data-type';
		$global['strongtype']['num']['format'] = '[num]';
		$global['strongtype']['num']['example'][] = 'https://gist.github.com/4378809';

		// NOT_INT
		$global['strongtype']['not_int']['short_description'] = 'N\'est pas un nombre entier';
		$global['strongtype']['not_int']['genre'] = 'data-type';
		$global['strongtype']['not_int']['format'] = '[not_int]';
		$global['strongtype']['not_int']['example'][] = 'https://gist.github.com/4374525';

		// INT
		$global['strongtype']['int']['short_description'] = 'Nombre entier';
		$global['strongtype']['int']['genre'] = 'data-type';
		$global['strongtype']['int']['format'] = '[int]';
		$global['strongtype']['int']['example'][] = 'https://gist.github.com/4374503';

		/////////////////
		//// SPECIAL ////
		/////////////////

		// SENTENCE
		$global['strongtype']['sentence']['short_description'] = 'Aide au repérage de phrases potentielles';
		$global['strongtype']['sentence']['genre'] = 'special';
		$global['strongtype']['sentence']['format'] = '[sentence]';
		$global['strongtype']['sentence']['example'][] = '';

		// STRING
		$global['strongtype']['string']['short_description'] = 'Strong-type historique.';
		$global['strongtype']['string']['genre'] = 'special';
		$global['strongtype']['string']['format'] = '[string]';
		$global['strongtype']['string']['example'][] = '';

		///////////////////
		//// FUNCTIONS ////
		///////////////////

		//////////////////////////
		//// SERVER TREATMENT ////
		//////////////////////////

		// BENCHMARK
		$global['function']['benchmark']['short_description'] = 'Retourne le temps d\'exécution d\'une fonction LBL.';
		$global['function']['benchmark']['type'] = 'server treatment';
		$global['function']['benchmark']['exec'] = 'variable';
		$global['function']['benchmark']['stability'] = 'stable';
		$global['function']['benchmark']['format'] = '{ BENCHMARK : fonction, [arguments] }';
		$global['function']['benchmark']['example'][] = 'https://gist.github.com/4499293';
		$global['function']['benchmark']['example'][] = 'https://gist.github.com/4499310';

		// DATE
		$global['function']['date']['short_description'] = 'Retourne et formate une date.';
		$global['function']['date']['type'] = 'server treatment';
		$global['function']['date']['exec'] = 'instantanée';
		$global['function']['date']['stability'] = 'stable';
		$global['function']['date']['format'] = '{ DATE : [jours supplémentaires], [format] }';
		$global['function']['date']['example'][] = 'https://gist.github.com/4373029';
		$global['function']['date']['example'][] = 'https://gist.github.com/4373038';

		// DNS
		$global['function']['dns']['short_description'] = 'Résout un domaine.';
		$global['function']['dns']['type'] = 'server treatment';
		$global['function']['dns']['exec'] = 'instantanée';
		$global['function']['dns']['stability'] = 'stable';
		$global['function']['dns']['format'] = '{ DNS : [ip] }';
		$global['function']['dns']['example'][] = 'https://gist.github.com/4399180';
		$global['function']['dns']['example'][] = 'https://gist.github.com/4399230';

		// IP
		$global['function']['ip']['short_description'] = 'Résout une IP.';
		$global['function']['ip']['type'] = 'server treatment';
		$global['function']['ip']['exec'] = 'instantanée';
		$global['function']['ip']['stability'] = 'stable';
		$global['function']['ip']['format'] = '{ IP : [domaine] }';
		$global['function']['ip']['example'][] = 'https://gist.github.com/4399296';
		$global['function']['ip']['example'][] = 'https://gist.github.com/4399314';

		// LANG
		$global['function']['lang']['short_description'] = 'Retourne la langue de l\'utilisateur.';
		$global['function']['lang']['type'] = 'server treatment';
		$global['function']['lang']['exec'] = 'instantanée';
		$global['function']['lang']['stability'] = 'en béta (pour les langues exotiques)';
		$global['function']['lang']['format'] = '{ LANG : [source] }';
		$global['function']['lang']['example'][] = 'https://gist.github.com/4399411';
		$global['function']['lang']['example'][] = 'https://gist.github.com/4399421';

		// OS
		$global['function']['os']['short_description'] = 'Retourne le système d\'exploitation de l\'utilisateur';
		$global['function']['os']['type'] = 'server treatment';
		$global['function']['os']['exec'] = 'instantanée';
		$global['function']['os']['stability'] = 'stable';
		$global['function']['os']['format'] = '{ OS }';
		$global['function']['os']['example'][] = 'https://gist.github.com/4499338';
		$global['function']['os']['example'][] = '';

		// SEARCH
		$global['function']['search']['short_description'] = 'Renvoi la recherche actuelle sans traitement préalable (telle qu\'elle a été tapée)';
		$global['function']['search']['type'] = 'server treatment';
		$global['function']['search']['exec'] = 'instantanée';
		$global['function']['search']['stability'] = 'stable';
		$global['function']['search']['format'] = '{ SEARCH : [traitements] }';
		$global['function']['search']['example'][] = 'https://gist.github.com/Linkbreakers/5343131';
		$global['function']['search']['example'][] = '';



		/////////////////////////
		//// GEOLOCALISATION ////
		/////////////////////////

		// GEO
		$global['function']['geo']['short_description'] = 'Géolocalise une IP.';
		$global['function']['geo']['type'] = 'geolocalisation';
		$global['function']['geo']['exec'] = 'lente';
		$global['function']['geo']['stability'] = 'en béta (parfois défaillant en dessous de AREA)';
		$global['function']['geo']['format'] = '{ GEO : [option], [ip] }';
		$global['function']['geo']['example'][] = 'https://gist.github.com/4401163';
		$global['function']['geo']['example'][] = 'https://gist.github.com/4401220';

		/////////////////////
		//// WEB-PARSING ////
		/////////////////////

		// HTML
		$global['function']['html']['short_description'] = 'Analyse une page HTML et retourne une donnée ciblée.';
		$global['function']['html']['type'] = 'web-parsing';
		$global['function']['html']['exec'] = 'lente';
		$global['function']['html']['stability'] = 'en alpha-test (privé)';
		$global['function']['html']['format'] = '{ HTML : url, chemin, [resultat], [position] }';
		$global['function']['html']['example'][] = 'https://gist.github.com/4542364';
		$global['function']['html']['example'][] = 'https://gist.github.com/4542465';
		$global['function']['html']['example'][] = 'https://gist.github.com/4542516';
		$global['function']['html']['example'][] = 'https://gist.github.com/4542615';
		$global['function']['html']['example'][] = 'https://gist.github.com/4542649';

		

		//////////////////////
		//// HTML-SHAPING ////
		//////////////////////

		// AUDIO
		$global['function']['audio']['short_description'] = 'Affiche un player audio.';
		$global['function']['audio']['type'] = 'html-shaping';
		$global['function']['audio']['exec'] = 'instantanée';
		$global['function']['audio']['stability'] = 'en béta-test (paramètres à améliorer)';
		$global['function']['audio']['format'] = '{ AUDIO : url }';
		$global['function']['audio']['example'][] = 'https://gist.github.com/4406365';
		$global['function']['audio']['example'][] = '';

		// GIST
		$global['function']['gist']['short_description'] = 'Affiche un script Gist (de Github).';
		$global['function']['gist']['type'] = 'html-shaping';
		$global['function']['gist']['exec'] = 'instantanée';
		$global['function']['gist']['stability'] = 'stable';
		$global['function']['gist']['format'] = '{ GIST : code }';
		$global['function']['gist']['example'][] = 'https://gist.github.com/4477851';

		// VIMEO
		$global['function']['vimeo']['short_description'] = 'Affiche une vidéo Vimeo.';
		$global['function']['vimeo']['type'] = 'html-shaping';
		$global['function']['vimeo']['exec'] = 'instantanée';
		$global['function']['vimeo']['stability'] = 'en béta-test (paramètres optionnels à faire évoluer)';
		$global['function']['vimeo']['format'] = '{ VIMEO : code, [largeur], [hauteur], [bordure] }';
		$global['function']['vimeo']['example'][] = 'https://gist.github.com/4478171';
		$global['function']['vimeo']['example'][] = '';

		// IMG
		$global['function']['img']['short_description'] = 'Affiche une image.';
		$global['function']['img']['type'] = 'html-shaping';
		$global['function']['img']['exec'] = 'instantanée';
		$global['function']['img']['stability'] = 'stable';
		$global['function']['img']['format'] = '{ IMG : url, [width], [height] }';
		$global['function']['img']['example'][] = 'https://gist.github.com/4406445';
		$global['function']['img']['example'][] = 'https://gist.github.com/Linkbreakers/5331964';

		// N
		$global['function']['n']['short_description'] = 'Retour à la ligne.';
		$global['function']['n']['type'] = 'html-shaping';
		$global['function']['n']['exec'] = 'instantanée';
		$global['function']['n']['stability'] = 'stable';
		$global['function']['n']['format'] = '{ N : [nombre] }';
		$global['function']['n']['example'][] = 'https://gist.github.com/4406521';
		$global['function']['n']['example'][] = '';

		// JS
		$global['function']['js']['short_description'] = 'Active un gadget Javascript.';
		$global['function']['js']['type'] = 'html-shaping';
		$global['function']['js']['exec'] = 'rapide';
		$global['function']['js']['stability'] = 'stable';
		$global['function']['js']['format'] = '{ JS : gadget, [spécifications] }';
		$global['function']['js']['example'][] = 'https://gist.github.com/4406740';
		$global['function']['js']['example'][] = 'https://gist.github.com/4406877';

		// CHRONO
		$global['function']['chrono']['short_description'] = 'Gadget chronomètre.';
		$global['function']['chrono']['type'] = 'html-shaping';
		$global['function']['chrono']['exec'] = 'rapide';
		$global['function']['chrono']['stability'] = 'en béta-test (paramètres à améliorer)';
		$global['function']['chrono']['format'] = '{ CHRONO : [seconde] }';
		$global['function']['chrono']['example'][] = 'https://gist.github.com/4406806';
		$global['function']['chrono']['example'][] = '';

		// TIMER
		$global['function']['timer']['short_description'] = 'Gadget compte à rebours.';
		$global['function']['timer']['type'] = 'html-shaping';
		$global['function']['timer']['exec'] = 'rapide';
		$global['function']['timer']['stability'] = 'en béta-test (paramètres à améliorer)';
		$global['function']['timer']['format'] = '{ TIMER : [initialisation], [action], [détails action] }';
		$global['function']['timer']['example'][] = 'https://gist.github.com/4406869';
		$global['function']['timer']['example'][] = '';

		//////////////////////
		//// CALCULATIONS ////
		//////////////////////

		// CALC
		$global['function']['calc']['short_description'] = 'Effectue un calcul.';
		$global['function']['calc']['type'] = 'calculations';
		$global['function']['calc']['exec'] = 'isntantanée';
		$global['function']['calc']['stability'] = 'instable (langage naturel à éviter pour le moment)';
		$global['function']['calc']['format'] = '{ CALC : calcul }';
		$global['function']['calc']['example'][] = 'https://gist.github.com/4406955';
		$global['function']['calc']['example'][] = 'https://gist.github.com/4406966';

		// TRY
		$global['function']['try']['short_description'] = 'Renvoi la première donnée valide (non vide) en suivant l\'ordre des arguments.';
		$global['function']['try']['type'] = 'calculations';
		$global['function']['try']['exec'] = 'instantanée';
		$global['function']['try']['stability'] = 'stable';
		$global['function']['try']['format'] = '{ TRY : choix1, [choix2], ... }';
		$global['function']['try']['example'][] = '';
		$global['function']['try']['example'][] = '';

		// PICK_RAND
		$global['function']['pick_rand']['short_description'] = 'Récupère une donnée au hasard.';
		$global['function']['pick_rand']['type'] = 'calculations';
		$global['function']['pick_rand']['exec'] = 'instantanée';
		$global['function']['pick_rand']['stability'] = 'stable';
		$global['function']['pick_rand']['format'] = '{ PICK_RAND : choix1, [choix2], ... }';
		$global['function']['pick_rand']['example'][] = 'https://gist.github.com/4406988';
		$global['function']['pick_rand']['example'][] = '';

		// RAND
		$global['function']['rand']['short_description'] = 'Retourne un nombre au hasard.';
		$global['function']['rand']['type'] = 'calculations';
		$global['function']['rand']['exec'] = 'instantanée';
		$global['function']['rand']['stability'] = 'stable';
		$global['function']['rand']['format'] = '{ RAND : [minimum], [maximum] }';
		$global['function']['rand']['example'][] = 'https://gist.github.com/4407015';
		$global['function']['rand']['example'][] = '';

		//////////////////////
		//// CONSTRUCTION ////
		//////////////////////

		// JSON
		$global['function']['json']['short_description'] = 'Convertis des données au format JSON';
		$global['function']['json']['type'] = 'construction';
		$global['function']['json']['exec'] = 'isntantanée';
		$global['function']['json']['stability'] = 'instable';
		$global['function']['json']['format'] = '{ JSON : element }';
		$global['function']['json']['example'][] = '';
		$global['function']['json']['example'][] = '';

		// DB
		$global['function']['db']['short_description'] = 'Affiche ou enregistre une donnée dans la base';
		$global['function']['db']['type'] = 'construction';
		$global['function']['db']['exec'] = 'isntantanée';
		$global['function']['db']['stability'] = 'instable';
		$global['function']['db']['format'] = '{ DB : label, [valeur] }';
		$global['function']['db']['example'][] = '';
		$global['function']['db']['example'][] = '';

		///////////////////
		//// CONDITION ////
		///////////////////

		// IF
		$global['function']['then']['short_description'] = 'Applique des instructions';
		$global['function']['then']['type'] = 'condition';
		$global['function']['then']['exec'] = 'instantanée';
		$global['function']['then']['stability'] = 'en béta-test';
		$global['function']['then']['format'] = '{ THEN : instructions }';
		$global['function']['then']['example'][] = '';
		$global['function']['then']['example'][] = '';

		// IF
		$global['function']['if']['short_description'] = 'Vérifie une condition';
		$global['function']['if']['type'] = 'condition';
		$global['function']['if']['exec'] = 'instantanée';
		$global['function']['if']['stability'] = 'en béta-test';
		$global['function']['if']['format'] = '{ IF : conditions }';
		$global['function']['if']['example'][] = '';
		$global['function']['if']['example'][] = '';

		// ELSEIF
		$global['function']['elseif']['short_description'] = 'Vérifie une condition si {IF} a échoué';
		$global['function']['elseif']['type'] = 'condition';
		$global['function']['elseif']['exec'] = 'instantanée';
		$global['function']['elseif']['stability'] = 'en béta-test';
		$global['function']['elseif']['format'] = '{ ELSEIF : conditions }';
		$global['function']['elseif']['example'][] = '';
		$global['function']['elseif']['example'][] = '';

		// ELSE
		$global['function']['else']['short_description'] = 'Exécute des instructions si les conditions précédentes ont échouées';
		$global['function']['else']['type'] = 'condition';
		$global['function']['else']['exec'] = 'instantanée';
		$global['function']['else']['stability'] = 'en béta-test';
		$global['function']['else']['format'] = '{ ELSE }';
		$global['function']['else']['example'][] = '';
		$global['function']['else']['example'][] = '';

		// ENDIF
		$global['function']['endif']['short_description'] = 'Point de terminaison des instructions sous conditions';
		$global['function']['endif']['type'] = 'condition';
		$global['function']['endif']['exec'] = 'instantanée';
		$global['function']['endif']['stability'] = 'en béta-test';
		$global['function']['endif']['format'] = '{ ENDIF }';
		$global['function']['endif']['example'][] = '';
		$global['function']['endif']['example'][] = '';

		//////////////////////////
		//// STRING-TREATMENT ////
		//////////////////////////

		// CLEAN
		$global['function']['clean']['short_description'] = 'Nettoie une chaîne de caractères.';
		$global['function']['clean']['type'] = 'strings-treatment';
		$global['function']['clean']['exec'] = 'instantanée';
		$global['function']['clean']['stability'] = 'stable';
		$global['function']['clean']['format'] = '{ CLEAN : phrase }';
		$global['function']['clean']['example'][] = 'https://gist.github.com/4407117';
		$global['function']['clean']['example'][] = '';

		// ENCODE
		$global['function']['encode']['short_description'] = 'Encode une chaîne de caractères.';
		$global['function']['encode']['type'] = 'strings-treatment';
		$global['function']['encode']['exec'] = 'instantanée';
		$global['function']['encode']['stability'] = 'stable';
		$global['function']['encode']['format'] = '{ ENCODE : type, phrase }';
		$global['function']['encode']['example'][] = 'https://gist.github.com/4407171';
		$global['function']['encode']['example'][] = '';

		// TINYURL
		$global['function']['tinyurl']['short_description'] = 'Compresse une adresse web (service tinyURL).';
		$global['function']['tinyurl']['type'] = 'strings-treatment';
		$global['function']['tinyurl']['exec'] = 'rapide (dépend du service)';
		$global['function']['tinyurl']['stability'] = 'stable';
		$global['function']['tinyurl']['format'] = '{ TINYURL : url }';
		$global['function']['tinyurl']['example'][] = 'https://gist.github.com/4507542';
		$global['function']['tinyurl']['example'][] = '';

		// URL
		$global['function']['url']['short_description'] = 'Encode une chaîne de caractères au format URL.';
		$global['function']['url']['type'] = 'strings-treatment';
		$global['function']['url']['exec'] = 'instantanée';
		$global['function']['url']['stability'] = 'stable';
		$global['function']['url']['format'] = '{ URL : phrase }';
		$global['function']['url']['example'][] = 'https://gist.github.com/4407195';
		$global['function']['url']['example'][] = '';

		// RAWURL
		$global['function']['rawurl']['short_description'] = 'Encode une chaîne de caractères au format URL (RFC3986).';
		$global['function']['rawurl']['type'] = 'strings-treatment';
		$global['function']['rawurl']['exec'] = 'instantanée';
		$global['function']['rawurl']['stability'] = 'stable';
		$global['function']['rawurl']['format'] = '{ RAWURL : phrase }';
		$global['function']['rawurl']['example'][] = 'https://gist.github.com/4407211';
		$global['function']['rawurl']['example'][] = '';

		// DECODE
		$global['function']['decode']['short_description'] = 'Décode une chaîne de caractères.';
		$global['function']['decode']['type'] = 'strings-treatment';
		$global['function']['decode']['exec'] = 'instantanée';
		$global['function']['decode']['stability'] = 'stable';
		$global['function']['decode']['format'] = '{ DECODE : type, phrase }';
		$global['function']['decode']['example'][] = 'https://gist.github.com/4407239';
		$global['function']['decode']['example'][] = '';

		// ENCRYPT
		$global['function']['encrypt']['short_description'] = 'Crypte une chaîne de caractères.';
		$global['function']['encrypt']['type'] = 'strings-treatment';
		$global['function']['encrypt']['exec'] = 'instantanée';
		$global['function']['encrypt']['stability'] = 'stable';
		$global['function']['encrypt']['format'] = '{ ENCRYPT : type, phrase }';
		$global['function']['encrypt']['example'][] = 'https://gist.github.com/4407338';
		$global['function']['encrypt']['example'][] = '';

		// MD5
		$global['function']['md5']['short_description'] = 'Crypte une chaîne de caractères en MD5.';
		$global['function']['md5']['type'] = 'strings-treatment';
		$global['function']['md5']['exec'] = 'instantanée';
		$global['function']['md5']['stability'] = 'stable';
		$global['function']['md5']['format'] = '{ MD5 : phrase }';
		$global['function']['md5']['example'][] = 'https://gist.github.com/4407425';
		$global['function']['md5']['example'][] = '';

		// STR_CRAZY
		$global['function']['str_crazy']['short_description'] = 'Change une chaîne en majuscule/minuscule successivement';
		$global['function']['str_crazy']['type'] = 'strings-treatment';
		$global['function']['str_crazy']['exec'] = 'instantanée';
		$global['function']['str_crazy']['stability'] = 'stable';
		$global['function']['str_crazy']['format'] = '{ STR_CRAZY : phrase }';
		$global['function']['str_crazy']['example'][] = 'https://gist.github.com/4515872';

		// STR_TINYURL
		$global['function']['str_tinyurl']['short_description'] = 'Compresse les URLs et retourne le texte correspondant (service tinyURL).';
		$global['function']['str_tinyurl']['type'] = 'strings-treatment';
		$global['function']['str_tinyurl']['exec'] = 'rapide (dépend du service)';
		$global['function']['str_tinyurl']['stability'] = 'stable';
		$global['function']['str_tinyurl']['format'] = '{ STR_TINYURL : texte }';
		$global['function']['str_tinyurl']['example'][] = 'https://gist.github.com/4507918';
		$global['function']['str_tinyurl']['example'][] = '';

		// STR_LOW
		$global['function']['str_low']['short_description'] = 'Change une chaîne en minuscule';
		$global['function']['str_low']['type'] = 'strings-treatment';
		$global['function']['str_low']['exec'] = 'instantanée';
		$global['function']['str_low']['stability'] = 'stable';
		$global['function']['str_low']['format'] = '{ STR_LOW : phrase }';
		$global['function']['str_low']['example'][] = 'https://gist.github.com/4407538';
		$global['function']['str_low']['example'][] = '';

		// STR_UP
		$global['function']['str_up']['short_description'] = 'Change une chaîne en majuscule';
		$global['function']['str_up']['type'] = 'strings-treatment';
		$global['function']['str_up']['exec'] = 'instantanée';
		$global['function']['str_up']['stability'] = 'stable';
		$global['function']['str_up']['format'] = '{ STR_UP : phrase }';
		$global['function']['str_up']['example'][] = 'https://gist.github.com/4407562';
		$global['function']['str_up']['example'][] = '';

		// STR_UP_FIRST
		$global['function']['str_up_first']['short_description'] = 'Change la première lettre en majuscule';
		$global['function']['str_up_first']['type'] = 'strings-treatment';
		$global['function']['str_up_first']['exec'] = 'instantanée';
		$global['function']['str_up_first']['stability'] = 'stable';
		$global['function']['str_up_first']['format'] = '{ STR_UP_FIRST : phrase, [action avant] }';
		$global['function']['str_up_first']['example'][] = 'https://gist.github.com/4407586';
		$global['function']['str_up_first']['example'][] = 'https://gist.github.com/4498791';

		/////////////////////////////
		//// ABOUT LINKBRREAKERS ////
		/////////////////////////////

		// LINKBREAKERS
		$global['function']['linkbreakers']['short_description'] = 'Renvoi l\'adresse du site Linkbreakers';
		$global['function']['linkbreakers']['type'] = 'about-linkbreakers';
		$global['function']['linkbreakers']['exec'] = 'instantanée';
		$global['function']['linkbreakers']['stability'] = 'stable';
		$global['function']['linkbreakers']['format'] = '{ LINKBREAKERS : [option] }';
		$global['function']['linkbreakers']['example'][] = 'https://gist.github.com/4407630';
		$global['function']['linkbreakers']['example'][] = '';

		// LBURL
		$global['function']['lburl']['short_description'] = 'Renvoi l\'adresse-type d\'une recherche sur Linkbreakers';
		$global['function']['lburl']['type'] = 'about-linkbreakers';
		$global['function']['lburl']['exec'] = 'instantanée';
		$global['function']['lburl']['stability'] = 'stable';
		$global['function']['lburl']['format'] = '{ LBURL : recherche, [espace personnel] }';
		$global['function']['lburl']['example'][] = 'https://gist.github.com/4407714';
		$global['function']['lburl']['example'][] = 'https://gist.github.com/Linkbreakers/5343313';

		// TEXT
		$global['function']['text']['short_description'] = 'Renvoi l\'adresse-type d\'une utilisation du tool Text';
		$global['function']['text']['type'] = 'about-linkbreakers';
		$global['function']['text']['exec'] = 'instantanée';
		$global['function']['text']['stability'] = 'stable';
		$global['function']['text']['format'] = '{ TEXT : contenu }';
		$global['function']['text']['example'][] = 'https://gist.github.com/4407758';
		$global['function']['text']['example'][] = '';

		// VERSION
		$global['function']['version']['short_description'] = 'Renvoi la version du noyau LBL';
		$global['function']['version']['type'] = 'about-linkbreakers';
		$global['function']['version']['exec'] = 'instantanée';
		$global['function']['version']['stability'] = 'stable';
		$global['function']['version']['format'] = '{ VERSION }';
		$global['function']['version']['example'][] = 'https://gist.github.com/4407926';
		$global['function']['version']['example'][] = '';

		//////////////////
		//// SEMANTIC ////
		//////////////////

		// STR_TO_NUM
		$global['function']['str_to_num']['short_description'] = 'Renvoi une entrée numérique depuis sa version littérale';
		$global['function']['str_to_num']['type'] = 'semantic';
		$global['function']['str_to_num']['exec'] = 'rapide';
		$global['function']['str_to_num']['stability'] = 'en béta-test (incomplet)';
		$global['function']['str_to_num']['format'] = '{ STR_TO_NUM : nombre }';
		$global['function']['str_to_num']['example'][] = 'https://gist.github.com/4408169';
		$global['function']['str_to_num']['example'][] = '';

		// DAY_RELATIVE
		$global['function']['day_relative']['short_description'] = 'Compréhension temporelle (dans N jours)';
		$global['function']['day_relative']['type'] = 'semantic';
		$global['function']['day_relative']['exec'] = 'standard';
		$global['function']['day_relative']['stability'] = 'en béta-test';
		$global['function']['day_relative']['format'] = '{ DAY_RELATIVE : phrase }';
		$global['function']['day_relative']['example'][] = 'https://gist.github.com/4409489';
		$global['function']['day_relative']['example'][] = '';

		// WEEK_RELATIVE
		$global['function']['week_relative']['short_description'] = 'Compréhension temporelle (dans N semaines)';
		$global['function']['week_relative']['type'] = 'semantic';
		$global['function']['week_relative']['exec'] = 'standard';
		$global['function']['week_relative']['stability'] = 'en béta-test';
		$global['function']['week_relative']['format'] = '{ WEEK_RELATIVE : phrase }';
		$global['function']['week_relative']['example'][] = 'https://gist.github.com/4409524';
		$global['function']['week_relative']['example'][] = '';

		// MONTH_RELATIVE
		$global['function']['month_relative']['short_description'] = 'Compréhension temporelle (dans N mois)';
		$global['function']['month_relative']['type'] = 'semantic';
		$global['function']['month_relative']['exec'] = 'standard';
		$global['function']['month_relative']['stability'] = 'en béta-test';
		$global['function']['month_relative']['format'] = '{ MONTH_RELATIVE : phrase }';
		$global['function']['month_relative']['example'][] = 'https://gist.github.com/4409561';
		$global['function']['month_relative']['example'][] = '';

		// YEAR_RELATIVE
		$global['function']['year_relative']['short_description'] = 'Compréhension temporelle (dans N années)';
		$global['function']['year_relative']['type'] = 'semantic';
		$global['function']['year_relative']['exec'] = 'standard';
		$global['function']['year_relative']['stability'] = 'en béta-test';
		$global['function']['year_relative']['format'] = '{ YEAR_RELATIVE : phrase }';
		$global['function']['year_relative']['example'][] = 'https://gist.github.com/4409591';
		$global['function']['year_relative']['example'][] = '';

		// NEARDAY_RELATIVE
		$global['function']['nearday_relative']['short_description'] = 'Compréhension temporelle (jour proche relatif)';
		$global['function']['nearday_relative']['type'] = 'semantic';
		$global['function']['nearday_relative']['exec'] = 'standard';
		$global['function']['nearday_relative']['stability'] = 'en béta-test';
		$global['function']['nearday_relative']['format'] = '{ NEARDAY_RELATIVE : phrase }';
		$global['function']['nearday_relative']['example'][] = 'https://gist.github.com/4409595';
		$global['function']['nearday_relative']['example'][] = 'https://gist.github.com/4409602';

		// DATE_RELATIVE
		$global['function']['date_relative']['short_description'] = 'Compréhension temporelle (jour, semaine, mois, année, relatif large)';
		$global['function']['date_relative']['type'] = 'semantic';
		$global['function']['date_relative']['exec'] = 'lente';
		$global['function']['date_relative']['stability'] = 'en béta-test';
		$global['function']['date_relative']['format'] = '{ DATE_RELATIVE : phrase }';
		$global['function']['date_relative']['example'][] = 'https://gist.github.com/4409627';
		$global['function']['date_relative']['example'][] = 'https://gist.github.com/4409644';

		// SUBAREA_TO_BIGCITY
		$global['function']['subarea_to_bigcity']['short_description'] = 'Déduit une grande ville d\'après un département';
		$global['function']['subarea_to_bigcity']['type'] = 'semantic';
		$global['function']['subarea_to_bigcity']['exec'] = 'standard';
		$global['function']['subarea_to_bigcity']['stability'] = 'en béta-test (incomplet, France uniquement)';
		$global['function']['subarea_to_bigcity']['format'] = '{ SUBAREA_TO_BIGCITY : département }';
		$global['function']['subarea_to_bigcity']['example'][] = 'https://gist.github.com/4412227';
		$global['function']['subarea_to_bigcity']['example'][] = '';

		// LANGUAGE_TO_SIGN
		$global['function']['language_to_sign']['short_description'] = 'Retourne le signe d\'une langue en fonction de la langue entrée';
		$global['function']['language_to_sign']['type'] = 'semantic';
		$global['function']['language_to_sign']['exec'] = 'standard';
		$global['function']['language_to_sign']['stability'] = 'en béta-test';
		$global['function']['language_to_sign']['format'] = '{ LANGUAGE_TO_SIGN : langue }';
		$global['function']['language_to_sign']['example'][] = 'https://gist.github.com/4412237';
		$global['function']['language_to_sign']['example'][] = '';



		if ($type === NULL) {

			return $global;

		} else {

			if ($focus === NULL) if (isset($global[$type])) return $global[$type]; else return $this->error;
			else if (isset($global[$type][$focus])) return $global[$type][$focus]; else return $this->error;

		}

	}

	/**
 	*
 	* Get a large amount of datas from the API-array
 	*
 	* @param string $type function/strongtype
 	* @param string $opt an option (e.g. 'order_by')
 	* @param string $opt_detail an option detail (e.g. 'type' for 'order_by')
 	* @return JSON
 	*
 	*/
	public function get_datas($type=NULL, $opt=NULL, $opt_detail=NULL) {

		if ($type === NULL) return json_encode($this->array_global());
		else {

			if ($opt === NULL) return json_encode($this->array_global($type));
			elseif ($opt === 'order_by') return json_encode($this->fetch_by_type($this->array_global($type), $opt_detail));

		}

	}

	/**
 	*
 	* Fetch Mustache arrays by 'type' and sort it as a different array
 	*
 	* @param string $arr_glob the global_array
 	* @param string $fetch_order the kind of order you want (e.g. 'type' or 'genre' or even 'stability')
 	* @return array
 	*
 	*/
	public function fetch_by_type($arr_glob, $fetch_order) {

		$arr_ordered_by_type = array();

		foreach ($arr_glob as $fun_name => $fun_content) {

			if (!isset($fun_content[$fetch_order])) continue;
			else {

				$actual_type = $fun_content[$fetch_order];
				$arr_ordered_by_type[$actual_type][] = $fun_name; 

			}

		}

		if (empty($arr_ordered_by_type)) return $this->error;
		else return $arr_ordered_by_type;

	}

	/**
 	*
 	* Get focused datas from the API-array
 	*
 	* @param string $type function/strongtype
 	* @param string $focus the name of a peculiar function/strongtype
 	* @return void
 	*
 	*/
	public function focus_datas($type=NULL, $focus=NULL) {

		return json_encode($this->array_global($type, $focus));

	}

}