<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeployKobotoolsForm implements ShouldQueue
{
    private $uid;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        
        $this->uid = $uid;
         
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
       
        $id = config('services.kobo.id');
        $password = config('services.kobo.password');
         $post = [
            'auth' => [$id, $password],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];

        
        $res = $client->request('GET', 'https://kf.kobotoolbox.org/imports/'.$this->uid, $post);
        $response = json_decode($res->getBody());


        if($response->status=="complete")
        {
            //Log::info(json_encode($response->messages->created[0]->uid));
            $get = [
                'auth' => [$id, $password],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'multipart' => [
                    [
                        'name' => 'active',
                        'contents' => 'true',
                    ]                  
                ]
                    
            ];
             $resp = $client->request('POST', 'https://kf.kobotoolbox.org/assets/'.$response->messages->created[0]->uid.'/deployment/', $get);

        } else {
            Log::error("Deploying new form to Kobotoolbox failed with error " . $response['status'] . ".");
            $response['error'] = "Request failed with HTTP error " . $response['status'] . ". Please contact your administrator.";

        }

        return $response;
    }
}