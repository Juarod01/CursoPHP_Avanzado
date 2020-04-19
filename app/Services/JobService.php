<?php

namespace App\Services;

use App\Models\Job;

class JobService
{
    public function deleteJob($id){
        
        $job = Job::where('Id', $params['id'])->delete();
    }
}
