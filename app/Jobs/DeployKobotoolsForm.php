<?php

namespace App\Jobs;

use App\Jobs\ShareFormToKobotools;
use App\Models\Project;
use App\Models\Projectxlsform;
use App\Models\Xlsform;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\Concerns\updateExistingPivot;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeployKobotoolsForm implements ShouldQueue
{
    private $uid;
    private $projectId;
    private $formId;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid, $projectId, $formId)
    {
        $this->uid = $uid;
        $this->projectId = $projectId;
        $this->formId = $formId;
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
        // Log::info($response);

        if($response->status=="complete")
        {
            Log::info(json_encode($response->messages->created[0]->uid));
            $new_uid = $response->messages->created[0]->uid;

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
           
            $resp = $client->request('POST', 'https://kf.kobotoolbox.org/assets/'.$new_uid.'/deployment/', $get);
            $proj_xls = DB::table('project_xlsform')->where('xlsform_id', $this->formId)->where('project_id', $this->projectId)->update(['deployed'=>1]);


            //Rename form
            //Log::info($new_uid);
            $this->renameForm($new_uid);
        }
        else {

            $this->handle();
        }
            
        return $response;
    }

    public function renameForm($uid)
    {

        $client = new Client();
        $form = Xlsform::find($this->formId);

        $id = config('services.kobo.id');
        $password = config('services.kobo.password');

        $get = [
                'auth' => [$id, $password],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'multipart' => [
                    [
                        'name' => 'name',
                        'contents' => $form->form_title,
                    ],
                    [
                        'name' => 'settings',
                        'contents' => '{"description":"'.$form->description.'"}',
                    ],
                    [
                        'name' => 'asset_type',
                        'contents' => 'survey',
                    ]

                ]

            ];
        $resp = $client->request('PATCH', 'https://kf.kobotoolbox.org/assets/'.$uid.'/', $get);   
        
        $this->updateProjForm($uid);

        $project = Project::find($this->projectId);
        $members = $project->users;

        foreach ($members as $member)
        {

            dispatch(new ShareFormToKobotools($this->formId,  $this->projectId, $member->kobo_id));
        }


        return $resp;

    }

    public function updateProjForm($uid)
    {
        // update form uid into project_xlsform
        DB::table('project_xlsform')->where('project_id', $this->projectId)->where('xlsform_id', $this->formId)->update(['form_kobo_id_string'=>$uid]);
        
        // update the status of the form
        DB::table('project_xlsform')->where('project_id', $this->projectId)->where('xlsform_id', $this->formId)->update(['deployed'=>'1']);

        return $uid;
        
    }

     public function getAssets()
    {

        $proj_xls = DB::table('project_xlsform')->where('project_id', $this->projectId)->where('xlsform_id', $this->formId)->get();
        $uid = $proj_xls[0]->form_kobo_id_string;
        $client = new Client();


        $id = config('services.kobo.id');
        $password = config('services.kobo.password');

        $get = [
                'auth' => [$id, $password],
                'headers' => [
                    'Accept' => 'application/json'
                    ]
                ];
        $resp = $client->request('GET', 'https://kf.kobotoolbox.org/assets/'.$uid.'/', $get);
        $response = json_decode($resp->getBody());
        //Log::info($response);
        return $response;
    }






}
