<?php
function parse_permalink($permalink) {
	$parse = parse_url($permalink);
	$path = str_replace('/docs/', '', $parse['path']);
	$path = str_replace('/', '', $path);
	return $path;
}
function page_content($permalink, $content) {

	$path = parse_permalink($permalink);
	
	if ($path == 'liste-des-fonctions') {
		$put = generate_listing_functions();
		foreach($put['type'] as $type) {
			$content = str_replace('%listing_' . $type . '%', $put['listing_' . $type], $content);
		}

	}

	if ($path == 'liste-des-strong-type') {
		$put = generate_listing_strong_type();
		foreach($put['genre'] as $genre) {
			$content = str_replace('%listing_' . $genre . '%', $put['listing_' . $genre], $content);
		}
	}

	return $content;

}

function infos_permalink($permalink) {

	$path = parse_permalink($permalink);

	// Strong type (alone)
	if (substr($path, 0, 11) == 'strong-type') {
		$datas['type'] = 'strong-type';
		$datas['function'] = substr($path, 12, strlen($path)-11);
		return $datas;
	}

	// Date (alone)
	if (substr($path, 0, 8) == 'fonction') {
		$datas['type'] = 'function';
		$datas['function'] = substr($path, 9, strlen($path)-8);
		return $datas;
	}

	return false;

	
}

function request_api($request) {
	$fget = @file_get_contents($request);
	if (!$fget) return false;

	$json = json_decode($fget, true);
	return $json;
}

function automatic_api($infos, $content) {


	/* Strong Type (alone)
	======================= */

	if ($infos['type'] == 'strong-type') {

		$results = request_api('http://www.linkbreakers.com/api/mustache/all/strongtype/' . $infos['function']);

		if ($results) {

			$new_content = $content;

			foreach($results as $key=>$result) {

				if ($key == 'example') {
					
					foreach ($result as $key=>$example) $new_content = str_replace('%example[' . $key . ']%', create_embed_gist($example), $new_content);
					
				} else {
					$new_content = str_replace('%' . $key . '%', $result, $new_content);
				}

			}

			$new_content = str_replace('%genre_label%', 'Fait partie du genre', $new_content);


			return $new_content;
		}

	}


	/* Functions (alone)
	======================= */
	if ($infos['type'] == 'function') {

		$results = request_api('http://www.linkbreakers.com/api/mustache/all/function/' . $infos['function']);

		if ($results) {

			$new_content = $content;

			foreach ($results as $key=>$result) {

				if ($key == 'example') {
					
					foreach ($result as $key=>$example) $new_content = str_replace('%example[' . $key . ']%', create_embed_gist($example), $new_content);
					
				} else {

					$new_content = str_replace('%' . $key . '%', $result, $new_content);

				}

			}

			$new_content = str_replace('%type_label%', 'Fait partie du type', $new_content);

			return $new_content;

		}
	}

	


	return $content;

}

function create_embed_gist($url) {

	return $url . '.js';
}



function generate_listing_functions() {

	$results = request_api('http://www.linkbreakers.com/api/mustache/all/function/');
	$header = '
	<table class="table table-striped">
	<thead>
	<tr>
	<th><strong>Nom</strong></th>
	<th><strong>Format</strong></th>
	<th><strong>Description</strong></th>
	</tr>
	</thead>
	<tbody>';

	$footer = '
	</tbody>
	</table>';


	$table = array();
	$type = array();

	// Loop functions
	foreach ($results as $key=>$function) {

		$value = str_replace(' ', '_', $function['type']);
		$value = str_replace('(', '', $value);
			$value = str_replace(')', '', $value);

			if (!in_array($value, $type)) $type[] = $value;


			$table[$value] .= '<tr>';
			$table[$value] .= '<th><strong><a  href="' . get_bloginfo('url') .  '/fonction-' . $key . '">' . $key . '</strong></th>';
			$table[$value] .= '<th><em>' . $function['format'] . '</em></th>';
			$table[$value] .= '<th>' . $function['short_description'] . '</th>';
			$table[$value] .= '</tr>';

		}

		foreach ($type as $value) $datas['listing_' . $value] = $header . $table[$value] . $footer;


		$datas['type'] = $type;

		return $datas;

	}

	function generate_listing_strong_type() {

		$results = request_api('http://www.linkbreakers.com/api/mustache/all/strongtype/');
		$header = '
		<table class="table table-striped">
		<thead>
		<tr>
	<th><strong>Nom</strong></th>
	<th><strong>Format</strong></th>
	<th><strong>Description</strong></th>
		</tr>
		</thead>
		<tbody>';

		$footer = '
		</tbody>
		</table>';

		$table = array();
		$genre = array();

	// Loop functions
		foreach ($results as $key=>$function) {

			$value = str_replace(' ', '_', $function['genre']);
			$value = str_replace('(', '', $value);
				$value = str_replace(')', '', $value);

				if (!in_array($value, $genre)) $genre[] = $value;

				$table[$value] .= '<tr>';
				$table[$value] .= '<th><strong><a href="' . get_bloginfo('url') .  '/strong-type-' . $key . '">' . $key . '</a></strong></th>';
				$table[$value] .= '<th><em>' . $function['format'] . '</em></th>';
				$table[$value] .= '<th>' . $function['short_description'] . '</th>';
				$table[$value] .= '</tr>';

			}


			foreach ($genre as $value) $datas['listing_' . $value] = $header . $table[$value] . $footer;


			$datas['genre'] = $genre;

			return $datas;
		}

