<?php if(!defined('DINGO')){die('External Access to File Denied');}

/**
 * Dingo Framework Basic Configuration File
 *
 * @Author          Evan Byrne
 * @Copyright       2008 - 2010
 * @Project Page    http://www.dingoframework.com
 */


// Application's Base URL
define('BASE_URL','http://php2.groupimage.net/belron_pp/products/');

// Application's Home URL
define('HOME_URL','http://php2.groupimage.net/belron_pp/access/');

// Application's Document upload
define('UPLOAD','/home/www/php2.groupimage.net/public/belron_pp/products/public/upload/');

// Application's Document root
define('ROOT','/belron_pp/products/');

// Does Application Use Mod_Rewrite URLs?
define('MOD_REWRITE',TRUE);

// Turn Debugging On?
define('DEBUG',FALSE);

// Turn Error Logging On?
define('ERROR_LOGGING',FALSE);

// Error Log File Location
define('ERROR_LOG_FILE','log.txt');


/**
 * Your Application's Default Timezone
 * Syntax for your local timezone can be found at
 * http://www.php.net/timezones
 */
date_default_timezone_set('America/New_York');


/* Auto Load Libraries */
config::set('autoload_library',array('url','db','session'));

/* Auto Load Helpers */
config::set('autoload_helper',array('functions'));


/* Sessions */
config::set('session',array(
    'connection'=>'default',
    'table'=>'access_sessions',
    'cookie'=>array('path'=>'/','expire'=>'+1 hours')
));

/* Notes */
config::set('notes',array('path'=>'/','expire'=>'+5 minutes'));


/* Application Folder Locations */
config::set('folder_views','view');             // Views
config::set('folder_controllers','controller'); // Controllers
config::set('folder_models','model');           // Models
config::set('folder_helpers','helper');         // Helpers
config::set('folder_languages','language');     // Languages
config::set('folder_errors','error');           // Errors
config::set('folder_orm','orm');                // ORM
