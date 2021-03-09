<?php
define("TOKEN", "y1nu7xbp6y94vp8knk9j9ppk11qi1ciao2lzhdcpyb3oa9wl0ju6hkt9nyg5frra"); // The secret token to add as a GitHub or GitLab secret, or otherwise as https://www.example.com/?token=secret-token
define("REMOTE_REPOSITORY", "git@github.com:pocketcompany/tellusart.git");           // The SSH URL to your repository
define("DIR", "/var/www/fund4/tellusart/");                                          // The path to your repostiroy; this must begin with a forward slash (/)
define("BRANCH", "refs/heads/master");                                               // The branch route
define("LOGFILE", "/dev/null"); //./deploy.log                                    // The name of the file you want to log to.
define("GIT", "/usr/bin/git");                                                       // The path to the git executable
define("AFTER_PULL", "npm run dev");                                                // A command to execute after successfully pulling

require_once("deployer.php");
