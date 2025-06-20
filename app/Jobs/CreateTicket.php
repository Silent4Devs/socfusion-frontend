<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alarmId;
    public $alarmType;
    public $title;
    public $create_ticket;
    public $comments_ticket;
    public $assign_ticket;

    /**
     * Create a new job instance.
     */
    public function __construct(
        $alarmId,
        $alarmType,
        $title,
        $create_ticket,
        $comments_ticket,
        $assign_ticket
    ) {
        $this->alarmId = $alarmId;
        $this->alarmType = $alarmType;
        $this->title = $title;
        $this->create_ticket = $create_ticket;
        $this->comments_ticket = $comments_ticket;
        $this->assign_ticket = $assign_ticket;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
  
    }

}
