<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Helpers\Helper;

use Log; 

use App\Setting;

use App\User;

use App\Channel;

use App\ChannelSubscription;

use App\VideoTape;

use App\BellNotification;

use App\BellNotificationTemplate;

class BellNotificationJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $data = $this->data;

            Log::info("BellNotificationJob - Start");

            $bell_notification_details = new BellNotification;

            $bell_notification_details->from_id = $data->from_id;

            $bell_notification_details->to_id = $data->to_id;

            $bell_notification_details->booking_id = $data->booking_id;

            $bell_notification_details->host_id = $data->host_id;

            $bell_notification_details->notification_type = $data->notification_type;

            $bell_notification_details->message = "Hello World";

            $bell_notification_details->redirection_type = BELL_NOTIFICATION_REDIRECT_HOST_VIEW;

            $bell_notification_details->save();

            Log::info("BellNotificationJob - End");

        } catch(Exception $e) {

            Log::info("BellNotificationJob - ERROR".print_r($e->getMessage(), true));
        }
    }
}
