<?php

namespace App\Console\Commands;

use App\Models\ByCode;
use Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
class checkcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:code';

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
        try {
            $byCodes= ByCode::whereNull('code')->get();
            foreach ($byCodes as $code){
                $res = Http::get("http://b210910.otp.com.vn/api/sessions/$code->session?token=5fd3985a8766cba1a6ad058d56930e96");
                if(array_key_exists('messages',$res['data'])){
                    $code->code =  $res['data']['messages'][0]['otp'];
                    $code->save();
                }
            }
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
}
