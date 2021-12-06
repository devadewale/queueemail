<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendBulkMailController extends Controller
{
    public function sendBulkMail(Request $request)
    {
        $token = $request['token'];
        $tokenarray = ['B4QKT2tEyDoxEM9NqRnA', 'RuTHSWG6mMIs83jFl2ct', 'FSx8dlXLnD8U6a8I41qb', 'UA2vvEe1GOGtExPNSXPG'];

       if ($token == '' ) {
            return response()->json([
                'message' => "Empty token passed"
            ],401);

       } elseif (!in_array($token, $tokenarray)) {
                return response()->json([
                    'message' => "Invalid token passed"
                ],401);
       } elseif (in_array($token, $tokenarray)) {
            $subject = $request['subject'];
            $body = $request['body'];
            $recipients = $request['recipients'];
            $attachments = $request['attachments'];
            $attachments = str_replace('data:image/png;base64,', '', $attachments);
            $attachments = str_replace(' ', '+', $attachments);
            $file_name = $request['filename'];

            // dd(base64_decode($attachments[0]));

            // send all mail in the queue.
            $job = (new \App\Jobs\SendBulkQueueEmail($subject, $body, $recipients, $attachments, $file_name))
                ->delay(
                    now()
                    ->addSeconds(2)
                );

                // dispatch(new \App\Jobs\SendBulkQueueEmail($subject));

            dispatch($job);

            echo "Bulk mail send successfully in the background...";
        } else {
            return response()->json([
                'message' => "Invali token passed"
            ],403);
        }
    }
}
