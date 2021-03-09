<?php
// The secret token to add as a GitHub or GitLab secret, or otherwise as https://www.example.com/?token=secret-token
define("TOKEN", "y1nu7xbp6y94vp8knk9j9ppk11qi1ciao2lzhdcpyb3oa9wl0ju6hkt9nyg5frra");
// The SSH URL to your repository
define("REMOTE_REPOSITORY", "git@config-allcoinwallet:pocketcompany/allcoinwallet.git");
// The path to your repostiroy; this must begin with a forward slash (/)
define("DIR", "/var/www/allcoinwallet/");
// The branch route
define("BRANCH", "refs/heads/master");        
// The name of the file you want to log to. ex) ./deploy.log                                       
define("LOGFILE", "/dev/null");
// The path to the git executable                                                 
define("GIT", "/usr/bin/git");
// A command to execute after successfully pulling
define("AFTER_PULL", "npm run prod");

require_once("deployer.php");
