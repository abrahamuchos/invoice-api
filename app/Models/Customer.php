<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int                                                                     $id
 * @property string                                                                  $name
 * @property string                                                                  $type
 * @property string                                                                  $email
 * @property string                                                                  $address
 * @property string                                                                  $city
 * @property string                                                                  $state
 * @property string                                                                  $country
 * @property string                                                                  $postal_code
 * @property \Illuminate\Support\Carbon|null                                         $created_at
 * @property \Illuminate\Support\Carbon|null                                         $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null                                                           $invoices_count
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withoutTrashed()
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'email',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
    ];
    protected static array $relationsToCascade = ['invoices'];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        //Soft delete cascade
        static::deleting(function ($resource){
            foreach (static::$relationsToCascade as $relation){
                foreach ($resource->{$relation}()->get() as $item){
                    $item->delete();
                }
            }
        });

        //Soft delete cascade restore
        static::restoring(function ($resource){
            foreach (static::$relationsToCascade as $relation){
                foreach ($resource->{$relation}()->withTrashed()->get() as $item){
                    $item->restore();
                }
            }
        });
    }

    /**
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }


}
