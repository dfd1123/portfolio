<?php
/*

    [ONLY IN DEVELOPMENT]
    #run 'visudo' and append below line.
    www-data ALL=NOPASSWD: ALL
    
*/

/* TEST */

define("TOKEN", "y1nu7xbp6y94vp8knk9j9ppk11qi1ciao2lzhdcpyb3oa9wl0ju6hkt9nyg5frra"); // The secret token to add as a GitHub or GitLab secret, or otherwise as https://www.example.com/?token=secret-token
define("REMOTE_REPOSITORY", "git@config-spowide:dfd1123/spowide_develop.git"); // The SSH URL to your repository
define("DIR", "/var/www/spowide_develop/"); // The path to your repostiroy; this must begin with a forward slash (/)
define("BRANCH", "refs/heads/master"); // The branch route
define("LOGFILE", "/dev/null"); // The name of the file you want to log to.

ob_start();
// Check if www-data has sudo with NOPASSWORD
if (shell_exec('sudo -n true') == "") {
    define("GIT", "sudo /usr/bin/git"); // The path to the git executable
    define("AFTER_PULL", ""); // A command to execute after successfully pulling
    echo "Auto deploy with post-merge running";
} else {
    define("GIT", "/usr/bin/git"); // The path to the git executable
    define("AFTER_PULL", "npm run dev"); // A command to execute after successfully pulling
    echo "Auto deploy with post-merge will not working";
}

ignore_user_abort(true);
$buffer_size = ob_get_length();
session_write_close();
header("Content-Encoding: none");
header("Content-Length: $buffer_size");
header("Connection: close");
ob_end_flush();
ob_flush();
flush();

require_once("deployer.php");
