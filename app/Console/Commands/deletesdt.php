<?php

namespace App\Console\Commands;

use App\Models\ByCode;
use DateTime;
use Illuminate\Console\Command;

class deletesdt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:sdt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = new DateTime;
        $date->modify('-5 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
       \App\Models\ByCode::whereNull('code')->whereDate('created_at','>',$formatted_date)->delete();
    }
}
