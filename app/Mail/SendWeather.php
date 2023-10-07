<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendWeather extends Mailable
{
    use Queueable, SerializesModels;

    private $info;
    private $measure;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($info, $measure)
    {
        $this->info = $info;
        $this->measure = $measure;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('weather', ['info' => $this->info, 'measure' => $this->measure]);
    }
}
