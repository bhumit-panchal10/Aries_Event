<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MailHelper
{
    /**
     * Send Partner With Us email
     *
     * @param string $name
     * @param string $email
     * @param string $mobile
     * @param string $dealId
     * @param array $dealData  // extra data for PDF
     * @param string $messageContent
     * @param string|null $subject
     * @return bool
     */
    public static function sendPartnerMail($data)
    {
        // Fetch mail config from DB

        $sendEmailDetails = DB::table('sendemaildetails')->where('id', 9)->first();

        if (!$sendEmailDetails) {
            Log::error('Mail config not found for ID 9');
            return false;
        }

        // Prepare message meta
        $msg = [
            'FromMail' => $sendEmailDetails->strFromMail,
            'Title' => $sendEmailDetails->strTitle,
            'ToEmail' => $data['Email'],
            'Subject' => $sendEmailDetails->strSubject ?? $data['Email'],
        ];

        // Prepare data for template
        $data = [
            'Name' => $data['Name'],
            'Email' => $data['Email'],
            'Mobile' => $data['Mobile'],
            'Message' => $data['Message'],
            'Subject' => $data['Subject'],
        ];

        try {
            Mail::send('emails.inquirymail', ['data' => $data], function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            return false;
        }
    }


    public static function sendDealDoneMail($dealData)
    {


        $sendEmailDetails = DB::table('sendemaildetails')->where('id', 10)->first();

        if (!$sendEmailDetails) {
            Log::error('Mail config not found for ID 10');
            return false;
        }
        // ✅ Calculate GST

        $amount = $dealData['Amount'] ?? 0;
        $gstPercent = $dealData['gst'] ?? 0;


        // $ActualAmount = $amount / (1 + ($gstPercent / 100));
        // $gstAmount = $amount - $ActualAmount;

        // $ActualAmount = round($ActualAmount);
        // $gstAmount = round($gstAmount);
        // dd($dealData);

        // ✅ Add to deal data
        // $dealData['gst'] = $gstAmount;
        // $dealData['total_amount'] = $ActualAmount;

        // ✅ Basic info

        $msg = [
            'FromMail' => $sendEmailDetails->strFromMail,
            'Title' => $sendEmailDetails->strTitle,
            'ToEmail' => $dealData['Email'],
            'Subject' => $sendEmailDetails->strSubject ?? 'Deal Done',
        ];

        // ✅ Mail data
        $data = [
            'Name' => $dealData['Name'],
            'Email' => $dealData['Email'],
            'Mobile' => $dealData['Mobile'],
            'DealId' => $dealData['inquiry_id'],
            'DealData' => $dealData,
        ];
        $invoiceno = $dealData['invoiceno'];

        // try {
        // ✅ Generate PDF using Blade view
        $pdf = PDF::loadView('emails.dealdone_pdf', ['data' => $data]);

        // ✅ Send the mail with PDF attachment
        $mail =   Mail::send('emails.dealdone_mail', ['data' => $data], function ($message) use ($msg, $pdf, $invoiceno) {
            $message->from($msg['FromMail'], $msg['Title']);
            $message->to($msg['ToEmail'])->subject($msg['Subject']);
            $message->attachData($pdf->output(), "Deal-{$invoiceno}.pdf");
        });


        return true;
        // } catch (\Exception $e) {
        //     Log::error('Deal Done mail failed: ' . $e->getMessage());
        //     return false;
        // }
    }
}
