<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('tasks:process-reminders')->everyMinute();
