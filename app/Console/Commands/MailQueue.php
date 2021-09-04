<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MailLog;
use App\Mail\PostPublishedMail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\DB;

class MailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:process';


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
        $mailQueue = MailLog::select(['mail_logs.id','posts.title', 'posts..description', 'subscribers.email', 'subscribers.name'])
            ->join('posts', 'posts.id', 'mail_logs.post_id')
            ->join('subscribers', 'subscribers.id', 'mail_logs.subscriber_id')
            ->where('mail_logs.status', 0)
            ->get();
        $mailer = app(Mailer::class);
        foreach ($mailQueue as $queue) {

            try {

                $mailer->queue(new PostPublishedMail([
                    'name' => $queue->name,
                    'email' => $queue->email,
                    'title' => $queue->title,
                    'description' => $queue->description
                ]));
                // Processed status
                DB::table('mail_logs')->where('id',$queue->id)->update(['status' => 1]);
                $this->info('Mail Processed:'.$queue->name);
            } catch (\Exception $ex) {
                $this->info('Failed  to send email:'.$ex->getMessage());
            }
        }
    }
}
