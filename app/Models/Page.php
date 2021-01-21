<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string|null $nameFR
 * @property string|null $nameEN
 * @property string|null $nameDE
 * @property string|null $name_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereNameDE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereNameEN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereNameFR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereNamePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends App {
	use Sortable;

	protected $primaryKey = 'id';
	protected $dates = [''];

	public static $rules = [
		'nameFR' => ['string', 'max:255'], 
		'nameEN' => ['string', 'max:255'], 
		'nameDE' => ['string', 'max:255'], 
		'name_path' => ['string', 'max:255'], 

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'nameFR' => 'string', 
		'nameEN' => 'string', 
		'nameDE' => 'string', 
		'name_path' => 'string', 

	];

	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/



	/******************************************************************************************************************
	/*********************************************** SCOPES ***********************************************************
	/*****************************************************************************************************************/

	/**
	 * @param Builder $builder
	 * @param $data
	 * @return Builder
	 */
	public function scopeResetPaginate(Builder $builder, $data){
		if (!empty($data['search'])){
			$terms = explode(' ', $data['search']);
			foreach($terms as $term){
				/*$builder = $builder
				->orWhere('name', 'like', '%'.$term.'%');*/
			}
			return $builder;
		}

		return $builder;
	}


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/

    /**
     * Return slug of page
     * @return string
     */
    public function getSlugAttribute(): string {
        $lang = strtoupper($this->getCurrentLang());
        return str_slug( $this->{'name'.$lang} );
    }
}
