<?php

namespace App\Models\Collaborator;

use Jenssegers\Mongodb\Eloquent\Model;

class SearchResult extends Model
{
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

    protected $connection = 'collaborator_mongodb';

    protected $collection = 'search_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['gtin', 'results'];
}
