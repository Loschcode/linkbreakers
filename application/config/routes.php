<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'home';
$route['404_override'] = 'home';

$route['home'] = 'home/index';

$route['search'] = 'search';
$route['redirect'] = 'search/clever_returns_pushback'; // Pretty name for the user
$route['search/(:any)'] = 'search/$1';

$route['lang/(:any)'] = 'lang/index/$1';

$route['autocomplete'] = 'autocomplete';
$route['autocomplete/(:any)'] = 'autocomplete/$1';

$route['autocomplete/search'] = 'autocomplete/search';
$route['autocomplete/search/(:any)'] = 'autocomplete/search/$1';

$route['autocomplete/search/alias'] = 'autocomplete/search/alias';
$route['autocomplete/search/alias/(:any)'] = 'autocomplete/search/alias/$1';

$route['autocomplete/search/from_user'] = 'autocomplete/search/from_user';
$route['autocomplete/search/from_user/(:any)'] = 'autocomplete/search/from_user/$1';

$route['tag'] = 'tag';
$route['tag/(:any)'] = 'tag/$1';

$route['log'] = 'log';
$route['log/(:any)'] = 'log/$1';


$route['profile'] = 'profile';
$route['profile/(:any)'] = 'profile/$1';

$route['admin'] = 'admin';
$route['admin/(:any)'] = 'admin/$1';

$route['api'] = 'api';

$route['tools'] = 'search/index'; // We redirect into index (we should redirect into documentation in next version)
$route['tools/(:any)'] = 'tools/$1';

$route['api/(:any)'] = 'api/$1';

//$route['search'] = 'search/index';
//$route['(:any)'] = 'search/$1';

// Test design homepage
$route['home/test'] = 'home/test';
$route['active_record_js/query'] = 'active_record_js/query';


// Must be at the end for security reasons
$route['([A-Za-z0-9_]+)/(:any)'] = 'search/from_user/$1/$2';

$route['([A-Za-z0-9_]+)'] = 'home/from_user/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */