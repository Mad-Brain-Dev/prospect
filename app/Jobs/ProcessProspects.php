<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessProspects implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $header;
    /**
     * Create a new job instance.
     */
    public function __construct($data, $header)
    {
        $this->data   = $data;
        $this->header = $header;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        foreach ($this->data as $item) {
//
//            $prospectData = array_combine($this->header, $item);
////            dd($prospectData);
//            Customer::create($prospectData);
//        }


        foreach ($this->data as $sale) {
            $saleData = array_combine($this->header, $sale);
            Customer::create($saleData);
        }
    }
}
