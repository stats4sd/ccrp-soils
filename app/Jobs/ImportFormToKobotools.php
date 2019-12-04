<?php

namespace App\Jobs;

use App\Jobs\DeployKobotoolsForm;
use App\Jobs\RedeployFormToKobotools;
use App\Jobs\ReplaceFormToKobotools;
use App\Jobs\ShareFormToKobotools;
use App\Models\Project;
use App\Models\Projectxlsform;
use App\Models\Xlsform;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportFormToKobotools implements ShouldQueue
{
     /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    private $formId;
    private $projectId;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($formId, $projectId)
    {
        $this->formId = $formId;
        $this->projectId = $projectId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {




        $kobo_id = DB::table('project_xlsform')->where('project_id', $this->projectId)->where('xlsform_id', $this->formId)->get();

        //if the
        if($kobo_id[0]->form_kobo_id_string != null)
        {
            dispatch(new ReplaceFormToKobotools($this->formId, $this->projectId));
            $project = Project::find($this->projectId);
            $members = $project->users;
            foreach($members as $member){
                dispatch(new ShareFormToKobotools($this->formId, $this->projectId, $member->kobo_id));
            }

        } else {

            $formId = $this->formId;
            $form = Xlsform::find($formId);

            // setup Guzzle Client info
            $client = new Client();

            $id = config('services.kobo.id');

            $password = config('services.kobo.password');

            // prepare payload for creating new form

            $post = [
                'auth' => [$id, $password],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'multipart' => [
                    [
                        'name' => 'library',
                        'contents' => 'false',
                    ],
                    [
                        'name' => 'file',
                        'contents' => Storage::disk('uploads')->get($form->path_file),
                        'filename' => 'text.xlsx',
                    ],
                    [
                        'name' => 'asset_type',
                        'contents' => 'survey',
                    ]
                ]
            ];

             // preprare response array;

            $response = [];

            try {
                // Send the request to Kobotoolbox
                $res = $client->request('POST', 'https://kf.kobotoolbox.org/imports/', $post);

                $status = $res->getStatusCode();

                Log::info($status);
                $response = [
                        'status' => $res->getStatusCode(),
                ];

                //POST request to imports returns 201 on success.
                if($response['status'] == 201) {

                    $response['data'] = json_decode($res->getBody());
                    $response = [
                            'uid' => $response['data']['uid']
                        ];
                }


                else {
                    Log::error("Posting new form to Kobotoolbox failed with error " . $response['status'] . ".");
                    $response['error'] = "Request failed with HTTP error " . $response['status'] . ". Please contact your administrator.";
                }
            }

            catch(\Exception $e) {
                //log error to debugging log
                Log::error($e->getMessage());

                //return error to user
                $response['error'] = $e->getMessage();
            }

            finally {
                //Deploy form
                dispatch(new DeployKobotoolsForm($response['data']->uid, $this->projectId, $this->formId));
              return $response;
            }
        }
    }
}
