<?php
//////////////////////////////
// LINKBREAKERS CONFIG FILE //
//////////////////////////////

// BASE //
const LINKBREAKERS_VERSION = '0.1.7b';
const LINKBREAKERS_URL = 'www.linkbreakers.com';
const LINKBREAKERS_DEFAULT_LANG = 'en';

// TOOLS //
const LINKBREAKERS_SANDBOX_REFRESH = '#sandbox ';

// ENTRY INSERTION //
const LINKBREAKERS_INSERTION_STATUS = 'alpha';

// SEARCH //
const LINKBREAKERS_NO_RESULT_REDIRECTION = 'http://google.com/#q={SEARCH:URL}';
const LINKBREAKERS_LINK_EXPIRATION = 2592000; // 1 months

// LINKS (without trailing slash)
const LINKBREAKERS_URL_EN = 'http://en.linkbreakers.com';
const LINKBREAKERS_URL_FR = 'http://fr.linkbreakers.com';
const LINKBREAKERS_DOC = 'http://www.linkbreakers.com/docs';

// DATABASE
const DB_TABLE_PREFIX = 'lb_'; // We repeat here for another usage

const DB_LOG_TABLE = 'lb_log';
const DB_SPACE_TABLE = 'lb_space';
const DB_PREFS_TABLE = 'lb_prefs';
const DB_RECOVERIES_TABLE = 'lb_recoveries';

const DB_TAGS_TABLE = 'lb_tags';
const DB_RESULTS_TABLE = 'lb_results';

const DB_VIRTUALDB_TABLE = 'lb_virtual_db';

// PROFILE
const DEFAULT_PROFILE_PICTURE = 'http://www.linkbreakers.com/assets/img/profile/default_profile_picture.png';

// SUFFIX/PREFIX errors
const SUFFIX_ERROR = '<span class="error-text pull-left"><i class="icon-ban-circle"></i> ';
const PREFIX_ERROR = '</span>';

// LBL SYSTEM
const LBL_LEFT_COMMENT = '/*'; // Don't forget to change the general_model.php line 530 to make it effective (regex)
const LBL_RIGHT_COMMENT = '*/'; // And also in the coloration system

// LBL ADD TAG SECURITIES
const LBL_STRING_RULES = 'callback_double_spaces|callback_var_restriction|callback_secure_string|callback_var_string_format';
const LBL_URL_RULES = 'callback_double_spaces|callback_secure_dyn_url|callback_brackets_format|callback_comments_format|callback_var_url_format|callback_check_linkedurl|callback_tools_check|callback_lbl_function_existence|callback_var_alone|callback_check_conditions_structure';

const LBL_URL_ALONE_RULES = 'callback_double_spaces|callback_secure_dyn_url|callback_brackets_format|callback_comments_format|callback_check_linkedurl|callback_tools_check|callback_lbl_function_existence|callback_check_conditions_structure';

?>