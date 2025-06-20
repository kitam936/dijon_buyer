<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MoveMail;

class SendMoveMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $move_info;
    public $user;

    // ✅ 正しく引数を受け取る
    public function __construct(array $user, array $move_info)
    {
        $this->move_info = $move_info;
        $this->user = $user;
    }

    public function handle(): void
    {
        Mail::to($this->user['email'])->send(new MoveMail($this->move_info, $this->user));
    }
}
