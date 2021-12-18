<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\Message;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkFcm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userType, $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userType, $message)
    {
        $this->userType = $userType;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userQuery = User::query();

        if($this->userType != 'all'){
            $userQuery->where('user_type', $this->userType);
        }

        $userQuery->whereNotNull('device_token')->chunk(100, function($users){
            foreach($users as $user){
                try {
                    $user->notify(new Message($this->message));
                } catch(Exception $ex){

                }
            }
        });
    }
}
