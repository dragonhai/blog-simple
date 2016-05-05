<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use App\Http\Controllers\SMSController;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSMSMessages extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $message
     */
    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SMSController $sms)
    {
        // $sms = new SMSController();
        // $sms->setTo($this->member->mobile);
        $sms->send($this->message);
    }
}
