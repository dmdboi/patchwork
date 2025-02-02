<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $form_id
 * @property string $model_type
 * @property int $model_id
 * @property string $status
 * @property mixed $payload
 * @property string $created_at
 * @property string $updated_at
 * @property Form $form
 */
class FormRequestMeta extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $fillable = ['form_request_id', 'model_id', 'model_type', 'key', 'value', 'created_at', 'updated_at'];

    protected $casts = [
        'value' => 'array',
    ];

    public function formRequest()
    {
        return $this->belongsTo('App\Models\FormRequest');
    }
}
