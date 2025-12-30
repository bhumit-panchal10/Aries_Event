<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

use App\Mail\BirthDayWish;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AutoBirthDayWish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:birthdaywith';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $users = User::where(['id' => 1])
        //     ->get();

        // $SendEmailDetails = DB::table('sendemaildetails')
        //     ->where(['id' => 11])
        //     ->first();

        // $msg = array(
        //     'FromMail' => $SendEmailDetails->strFromMail,
        //     'Title' => $SendEmailDetails->strTitle,
        //     'ToEmail' => "dev2.apolloinfotech@gmail.com",
        //     'Subject' => $SendEmailDetails->strSubject
        // );
        // $email = "dev2.apolloinfotech@gmail.com";

        // $mail = Mail::send(new BirthDayWish($msg['ToEmail']));

        //Mail::send(new BirthDayWish($users));

        // if ($users->count() > 0) {
        //     foreach ($users as $user) {
        //         $email = $user->email;

        //     }
        // }
        $details = [
            'title' => 'Thank you for subscribing to my newsletter',
            'body' => 'You will receive a newsletter every Fourth Friday of the month'

        ];

        Mail::to('dev2.apolloinfotech@gmail.com')->send(new BirthDayWish($details));


        //$mail = Mail::send(new BirthDayWish($details)->to('dev2.apolloinfotech@gmail.com'));



        return 0;
    }
}
