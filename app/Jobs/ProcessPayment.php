<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPayment implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Payment $payment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // breve tempo di elaborazione
        sleep(4);

        // faccio finta che il pagamento abbia un tasso di successo dell'80%
        $success = rand(1,100) <= 80;

        if ($success) {
            $this->payment->update([
                'status' => 'success',
                'transaction_id' => 'tr_' . uniqid(),
            ]);
        } else {
            $this->payment->update([
                'status' => 'failed'
            ]);
        }
    }
}
