# PHP Crontab Manager 
## Installation

Installation with Composer

Add in composer.json
~~~
    "require": {
        ...
        "kfosoft/php-crontab-manager":"1.0"
    }
~~~

Well done!

## Methods
##### onMinute($minute) : Set minute or minutes.
##### onHour($hour) : Set hour or hours.
##### onDayOfMonth($dayOfMonth) : Set day of month or days of month. 
##### onMonth($month) : Set month or months.
##### onDayOfWeek($dayOfWeek) : Set day of week or days of week.
##### on($timeCode) : Set entire time code with one function. This has to be a complete entry. See http://en.wikipedia.org/wiki/Cron#crontab_syntax
##### doJob($job) : Add job to the jobs array. Each time segment should be set before calling this method. The job should include the absolute path to the commands being used.
##### setCrontabPath($path) : location of the crontab executable. Default /usr/bin/crontab.
##### setDestinationPath($path) : location to save the crontab file.
##### activate($includeOldJobs = true) : Save the jobs to disk, remove existing cron.
##### clearJobs() : lear array jobs.
##### clearJobsFile() : Clear jobs file.
##### listJobs() : List current cron jobs.
 
## Example
~~~
$crontab = new /kfosoft/helpers/CronTab();
$crontab->onMinute(10)->onHour(0)->doJob('echo "job is work." >> /home/user/test')->activate();
~~~

Enjoy, guys!
