<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Log;

class SendBulkQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $subject, $body, $recipients, $attachments, $file_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $body, $recipients, $attachments, $file_name)
    {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $recipients = $this->recipients;
        $subjects = $this->subject;
        $body= $this->body;
        $count = 0;
        $file_name = $this->file_name;
        $attachments = $this->attachments;



        foreach ($recipients as $key => $value) {
            $email= $value;
            try {
                Mail::send('emails.test', ["messge_body"=>$body[$count]], function($message) use($subjects, $email, $count, $attachments, $file_name){
                    $message->to($email)->subject($subjects[$count]);

                    if (array_key_exists($count, $attachments)) {
                        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $attachments[$count]) && $file_name[$count]) {
                            $message->attachData(base64_decode($attachments[$count]), $file_name[$count]);
                        }
                    }
                });
                Log::channel('sentemail')->info('Sent Email to' . $email. ' subject ' . $subjects[$count]. ' body ' .$body[$count]);
            } catch (Exception $e){
                Log::info('Mail send Failed', ['message' => $e->getMessage()]);
            }
            $count++;
        }
    }
}
