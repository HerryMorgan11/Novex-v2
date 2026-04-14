<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class TestEmail extends Mailable
{
    use MailerSendTrait, Queueable, SerializesModels;

    public function build()
    {
        // Recipient for use with variables and/or personalization
        $to = Arr::get($this->to, '0.address');

        return $this
            ->view('emails.test_html')
            ->text('emails.test_text')
            ->attachFromStorageDisk('public', 'example.png')
            // Additional options for MailerSend API features
            ->mailersend(
                template_id: null,
                tags: ['tag'],
                personalization: [
                    new Personalization($to, [
                        'var' => 'variable',
                        'number' => 123,
                        'object' => [
                            'key' => 'object-value',
                        ],
                        'objectCollection' => [
                            [
                                'name' => 'John',
                            ],
                            [
                                'name' => 'Patrick',
                            ],
                        ],
                    ]),
                ],
                precedenceBulkHeader: true,
                sendAt: new Carbon('2022-01-28 11:53:20'),
            );
    }
}
