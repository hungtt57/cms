<?php

namespace App\Jobs\ProductReview;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Enterprise\ProductReview\Product;

class ImportProductJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $gtin;
    protected $maxReview;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $gtin, $maxReview)
    {
        $this->gtin = $gtin;
        $this->maxReview = $maxReview;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $gtin = $this->gtin;
        $maxReview = $this->maxReview;

        foreach ($gtin as $number) {
            $product = Product::firstOrCreate([
                'gtin' => $number,
            ]);
            $product->update(['max_review' => $maxReview]);
        }
    }
}
