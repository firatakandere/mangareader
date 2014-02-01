<?php

$lang = array(

// Common
    'GENERAL_ERROR' => 'General Error',
    'NOTIFY_ADMIN_EMAIL'    => 'Please notify the board administrator or webmaster: <a href="mailto:%1$s">%1$s</a>',
    'RETURN_INDEX'  => '%sReturn to the index page%s',
    'SUBMIT'    => 'Submit',
    'INFORMATION'   =>  'Information',
    'REMOVE_INSTALL_PATH'   => 'Your installation path is still available. Please delete or rename it.',

// Language attributes
    'CHARSET'   => 'UTF-8',
    'DIRECTION' => 'ltr', // left-to-right

// user management section
    'EMAIL_ADDRESS' => 'Email Address',
    'EMAIL_ADDRESS_CONFIRM' => 'Email Address (Confirm)',
    'LOGIN'     => 'Login',
    'USERNAME'  => 'Username',
    'PASSWORD'  => 'Password',
    'PASSWORD_CONFIRM'  => 'Password (Confirm)',
    'REGISTER'  => 'Register',
    'LOGOUT'    => 'Logout',
    'LOGIN_SUCCESSFUL'  => 'You have logged in successfully, redirecting...', // @todo make this better

    'PERMISSION_DENIED' => 'You don\'t have permission to reach this page',


// registration errors
    'TOO_SHORT_USERNAME'    => 'Username is too short',
    'TOO_LONG_USERNAME'     => 'Username is too long',
    'INVALID_CHARS_USERNAME'=> 'Username includes invalid characters',
    'TOO_SHORT_PASSWORD'    => 'Password is too short',
    'TOO_LONG_PASSWORD'     => 'Password is too long',
    'TOO_SHORT_PASSWORD_CONFIRM'    => 'Confirmation password is too short',
    'TOO_LONG_PASSWORD_CONFIRM'     => 'Confirmation password is too long',
    'TOO_SHORT_EMAIL'       => 'Email address is too short',
    'TOO_LONG_EMAIL'        => 'Email address is too long',
    'EMAIL_INVALID_EMAIL'   => 'Email address is not valid',
    'TOO_SHORT_EMAIL_CONFIRM'       => 'Confirmation email address is too short',
    'TOO_LONG_EMAIL_CONFIRM'        => 'Confirmation email address is too long',
    'PASSWORD_MATCH_ERROR'  => 'Passwords do not match',
    'EMAIL_MATCH_ERROR'     => 'Email addresses do not match',
    'TOO_SMALL_TZ'          => 'Timezone value is too small',
    'TOO_LARGE_TZ'          => 'Timezone value is too large',
    'REGISTERS_CLOSED'      => 'Registrations are currently closed',

// login errors
    'LOGIN_EMPTY_USERNAME'  => 'You cannot login without a username',
    'LOGIN_EMPTY_PASSWORD'  => 'You cannot login without a password',
    /**
    * @todo add forgot password section when it's done
    */
    'LOGIN_INVALID'         => 'Invalid username or password',


    'CACHE_NOT_WRITABLE'    => 'Cache directory is not writable',

// search section
    'SEARCH'    => 'Search',
    'SEARCH_BUTTON' => 'Go!',


// time zones
    'TIMEZONE'  => 'Timezone',
    'timezones'	=> array(
        '-12'	=> '[UTC - 12] Baker Island Time',
        '-11'	=> '[UTC - 11] Niue Time, Samoa Standard Time',
        '-10'	=> '[UTC - 10] Hawaii-Aleutian Standard Time, Cook Island Time',
		'-9.5'	=> '[UTC - 9:30] Marquesas Islands Time',
		'-9'	=> '[UTC - 9] Alaska Standard Time, Gambier Island Time',
		'-8'	=> '[UTC - 8] Pacific Standard Time',
		'-7'	=> '[UTC - 7] Mountain Standard Time',
		'-6'	=> '[UTC - 6] Central Standard Time',
		'-5'	=> '[UTC - 5] Eastern Standard Time',
		'-4.5'	=> '[UTC - 4:30] Venezuelan Standard Time',
		'-4'	=> '[UTC - 4] Atlantic Standard Time',
		'-3.5'	=> '[UTC - 3:30] Newfoundland Standard Time',
		'-3'	=> '[UTC - 3] Amazon Standard Time, Central Greenland Time',
		'-2'	=> '[UTC - 2] Fernando de Noronha Time, South Georgia &amp; the South Sandwich Islands Time',
		'-1'	=> '[UTC - 1] Azores Standard Time, Cape Verde Time, Eastern Greenland Time',
		'0'		=> '[UTC] Western European Time, Greenwich Mean Time',
		'1'		=> '[UTC + 1] Central European Time, West African Time',
		'2'		=> '[UTC + 2] Eastern European Time, Central African Time',
		'3'		=> '[UTC + 3] Moscow Standard Time, Eastern African Time',
		'3.5'	=> '[UTC + 3:30] Iran Standard Time',
		'4'		=> '[UTC + 4] Gulf Standard Time, Samara Standard Time',
		'4.5'	=> '[UTC + 4:30] Afghanistan Time',
		'5'		=> '[UTC + 5] Pakistan Standard Time, Yekaterinburg Standard Time',
		'5.5'	=> '[UTC + 5:30] Indian Standard Time, Sri Lanka Time',
		'5.75'	=> '[UTC + 5:45] Nepal Time',
		'6'		=> '[UTC + 6] Bangladesh Time, Bhutan Time, Novosibirsk Standard Time',
		'6.5'	=> '[UTC + 6:30] Cocos Islands Time, Myanmar Time',
		'7'		=> '[UTC + 7] Indochina Time, Krasnoyarsk Standard Time',
		'8'		=> '[UTC + 8] Chinese Standard Time, Australian Western Standard Time, Irkutsk Standard Time',
		'8.75'	=> '[UTC + 8:45] Southeastern Western Australia Standard Time',
		'9'		=> '[UTC + 9] Japan Standard Time, Korea Standard Time, Chita Standard Time',
		'9.5'	=> '[UTC + 9:30] Australian Central Standard Time',
		'10'	=> '[UTC + 10] Australian Eastern Standard Time, Vladivostok Standard Time',
		'10.5'	=> '[UTC + 10:30] Lord Howe Standard Time',
		'11'	=> '[UTC + 11] Solomon Island Time, Magadan Standard Time',
		'11.5'	=> '[UTC + 11:30] Norfolk Island Time',
		'12'	=> '[UTC + 12] New Zealand Time, Fiji Time, Kamchatka Standard Time',
		'12.75'	=> '[UTC + 12:45] Chatham Islands Time',
		'13'	=> '[UTC + 13] Tonga Time, Phoenix Islands Time',
		'14'	=> '[UTC + 14] Line Island Time',
	),

    // Pre-defined Groups Names
    'INACTIVE_USERS'    => 'Inactive users',
    'REGISTERED_USERS'  => 'Registered users',
    'GUESTS'            => 'Guests',
    'GLOBAL_MODERATORS' => 'Global moderators',
    'ADMINISTRATORS'    => 'Administrators',

    // default categories hook
    'CATEGORIES'    => 'Categories',
    'NEW_CATEGORY'  => 'Add Category',
    'CATEGORY_NAME' => 'Category Name',
    'IS_ADULT'      => 'Is Adult?',

);
?>
