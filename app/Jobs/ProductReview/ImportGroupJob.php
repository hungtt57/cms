<?php

namespace App\Jobs\ProductReview;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Social\MGroup;
use App\Models\Enterprise\ProductReview\Group;

class ImportGroupJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $groups;
    protected $maxReview;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $groups, $maxReview)
    {
        $this->groups = $groups;
        $this->maxReview = $maxReview;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $groups = MGroup::whereIn('_id', $this->groups)->with('groupType')->get();
        $maxReview = $this->maxReview;

        foreach ($groups as $group) {
            if ($group->groupType) {
                $g = Group::firstOrCreate([
                    'icheck_id' => $group->id,
                ]);
                $g->update([
                    'name' => $group->name,
                    'categories' => $group->groupType->categories_refer,
                    'max_review' => $maxReview,
                ]);
            }
        }
    }
}
