<?php
namespace kfosoft\helpers;

/**
 * CronTab manager.
 * @package app\services
 * @version 1.0
 * @copyright (c) 2014-2015 KFOSoftware Team <kfosoftware@gmail.com>
 */
class CronTab
{

    /** @var string location of the crontab executable. */
    public $crontab = '/usr/bin/crontab';

    /** @var string location to save the crontab file. */
    public $destination = '/tmp/CronManager';

    /** @var string minute (0 - 59). */
    protected $minute = 0;

    /** @var string hour (0 - 23). */
    protected $hour = 10;

    /** @var string day of month (1 - 31). */
    protected $dayOfMonth = '*';

    /**@var string month (1 - 12) or jan,feb,mar,apr... */
    protected $month = '*';

    /** @var string day of week (0 - 6) (sunday=0 or 7) or sun,mon,tue,wed,thu,fri,sat */
    protected $dayOfWeek = '*';

    /** @var array jobs. */
    protected $jobs = [];

    /**
     * Set minute or minutes
     * @param string $minute required
     * @return $this
     */
    public function onMinute($minute)
    {
        $this->minute = $minute;
        return $this;
    }

    /**
     * Set hour or hours
     * @param string $hour required
     * @return $this
     */
    public function onHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }

    /**
     * Set day of month or days of month
     * @param string $dayOfMonth required
     * @return $this
     */
    public function onDayOfMonth($dayOfMonth)
    {
        $this->dayOfMonth = $dayOfMonth;
        return $this;
    }

    /**
     * Set month or months
     * @param string $month required
     * @return $this
     */
    public function onMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Set day of week or days of week
     * @param string $dayOfWeek required
     * @return $this
     */
    public function onDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    /**
     * Set entire time code with one function.
     * This has to be a complete entry. See http://en.wikipedia.org/wiki/Cron#crontab_syntax
     * @param string $timeCode required
     * @return $this
     */
    public function on($timeCode)
    {
        list($this->minute, $this->hour, $this->dayOfMonth, $this->month, $this->dayOfWeek) = explode(' ', $timeCode);

        return $this;
    }

    /**
     * Add job to the jobs array. Each time segment should be set before calling this method.
     * The job should include the absolute path to the commands being used.
     * @param string $job required
     * @return $this
     */
    public function doJob($job)
    {
        $this->jobs[] = "{$this->minute} {$this->hour} {$this->dayOfMonth} {$this->month} {$this->dayOfWeek} {$job}";

        return $this;
    }

    /**
     * @param string $path location of the crontab executable. Default /usr/bin/crontab.
     * @return $this
     */
    public function setCrontabPath($path)
    {
        $this->crontab = $path;
        return $this;
    }

    /**
     * @param string $path location to save the crontab file.
     * @return $this
     */
    public function setDestinationPath($path)
    {
        $this->destination = $path;
        return $this;
    }

    /**
     * Save the jobs to disk, remove existing cron
     * @param boolean $includeOldJobs optional
     * @return boolean
     */
    public function activate($includeOldJobs = true)
    {
        $result = false;
        $contents = implode("\n", $this->jobs) . "\n";

        if ($includeOldJobs) {
            $contents .= $this->listJobs();
        }

        if (is_writable($this->destination) || !file_exists($this->destination)) {

            $this->clearJobsFile();

            file_put_contents($this->destination, $contents, LOCK_EX);

            exec("{$this->crontab} {$this->destination};");
            $result = true;
        }

        return $result;
    }

    /**
     * Clear array jobs.
     */
    public function clearJobs()
    {
       $this->jobs = [];
    }

    /**
     * Clear jobs file.
     * @return string exec result.
     */
    public function clearJobsFile()
    {
        return exec($this->crontab . ' -r;');
    }

    /**
     * List current cron jobs
     * @return string
     */
    public function listJobs()
    {
        return exec($this->crontab . ' -l;');
    }
}
