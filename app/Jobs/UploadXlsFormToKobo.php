<?php

namespace App\Jobs;

use App\Models\Xlsform;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadXlsFormToKobo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $form;
    public $importUid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Xlsform $form)
    {
        //
        $this->form = $form;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $response = Http::withBasicAuth(config('services.kobo.username'), config('services.kobo.password'))
            ->withHeaders(["Accept" => "application/json"])
            ->attach(
                'file',
                file_get_contents( public_path('uploads/'.$this->form->xlsfile)),
                Str::slug($this->form->title)
            )
            ->post(config('services.kobo.endpoint').'/imports/', [
                'destination' => config('services.kobo.endpoint_v2').'assets/'.$this->form->kobo_id.'/',
                'assetUid' => $this->form->kobo_id,
                'name' => $this->form->title,
            ])
            ->throw()
            ->json();

        \Log::info("importing");
        \Log::info($response);

        $this->importUid = $response['uid'];

        $importStatus = 'processing';

        while ($importStatus === 'processing') {
            $importCheck = $this->checkImport();
            \Log::info("importCheck");
            \Log::info($importCheck);
            $importStatus = $importCheck['status'];
        }

        \Log::info("imported");
        \Log::info($importCheck);

        if($importStatus === 'error') {
            // throw error back to user
        }








    }

    public function checkImport ()
    {
        // check import is complete;
        return Http::withBasicAuth(
            config('services.kobo.username'), config('services.kobo.password')
        )
            ->withHeaders(["Accept" => "application/json"])
            ->get(config('services.kobo.endpoint').'/imports/'.$this->importUid.'/')
            ->throw()
            ->json();
    }

}
