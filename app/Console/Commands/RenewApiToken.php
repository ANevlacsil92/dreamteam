<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RenewApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew:api token';

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
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.facebook.com/v17.0/oauth/access_token?client_id=198970276507814&client_secret=ca6692dc5ecc9b326be57129bc0896c4&grant_type=fb_exchange_token&fb_exchange_token=' . env('FACEBOOK_GRAPH_TOKEN'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        $newToken = $response['access_token'];

        // Store the new token in the .env file
        file_put_contents(base_path('.env'), str_replace(
            'FACEBOOK_GRAPH_TOKEN=' . env('FACEBOOK_GRAPH_TOKEN'),
            'FACEBOOK_GRAPH_TOKEN=' . $newToken,
            file_get_contents(base_path('.env'))
        ));
    }
}
