<?php

namespace App\Transformers;

use App\Models\Enterprise\MICheckReport;
use League\Fractal;

class ReportTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    public function transform(MICheckReport $report)
    {
        return [
            'type'  => $report->type,
        ];
    }
}
