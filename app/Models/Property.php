<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use const http\Client\Curl\AUTH_ANY;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

/**
 * App\Models\Property
 *
 * @property int $id
 * @property string|null $code_immo
 * @property string|null $titleFR
 * @property string|null $titleEN
 * @property string|null $titleDE
 * @property string|null $descriptionFR
 * @property string|null $descriptionEN
 * @property string|null $descriptionDE
 * @property int|null $type_property
 * @property int|null $area
 * @property int|null $nb_room
 * @property int|null $bedroom
 * @property int|null $floor
 * @property string|null $zipcode
 * @property string|null $city
 * @property string|null $country
 * @property int|null $construction_year
 * @property int|null $nb_bathroom
 * @property int|null $nb_wc
 * @property int|null $price
 * @property string|null $kitchen
 * @property string|null $mitoyen
 * @property \Illuminate\Support\Carbon|null $date_offer
 * @property \Illuminate\Support\Carbon|null $date_updated_immo
 * @property string|null $images_path
 * @property string|null $thumbnail_path
 * @property string|null $type
 * @property bool|null $favorite
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $living_area
 * @property int|null $sejour_area
 * @property int|null $field_area
 * @property int|null $carrez_area
 * @property string|null $address
 * @property string|null $address2
 * @property int|null $nb_waterroom
 * @property int|null $nb_cave
 * @property int|null $bedroom_rdc
 * @property int|null $one_level
 * @property int|null $has_furniture
 * @property int|null $interphone
 * @property int|null $garden
 * @property int|null $double_sejour
 * @property string|null $general_state
 * @property string|null $standing
 * @property string|null $type_parking
 * @property string|null $exterior_state
 * @property string|null $sanitation
 * @property string|null $alarm
 * @property string|null $new_old
 * @property string|null $expo_sejour
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Favorite[] $favorites
 * @property-read string $slug
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property resetPaginate($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property getByTypeProperty($data)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property getFavorites()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereBedroom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereCodeImmo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDateOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDateUpdatedImmo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDescriptionFR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDescriptionEN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDescriptionDE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereTypeProperty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereFavorite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereNbRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereNbBathroom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereNbWc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereKitchen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereMitoyen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereTitleFR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereTitleEN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereTitleDE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereImagesPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereBedroomRdc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereCarrezArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereConstructionYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereDoubleSejour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereExpoSejour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereExteriorState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereFieldArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereGarden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereGeneralState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereHasFurniture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereInterphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereLivingArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereNbCave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereNbWaterroom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereNewOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereOneLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereSanitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereSejourArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereStanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereTypeParking($value)
 * @mixin \Eloquent
 */
class Property extends App {
	use Sortable;

	protected $primaryKey = 'id';
	protected $dates = ['date_offer', 'date_updated_immo'];

	public const FAVORITE = 1;
	public const NOT_FAVORITE = 0;

	public const TYPE_HOUSE    = 1;
	public const TYPE_FLAT     = 2;
	public const TYPE_BUILDING = 3;
	public const TYPE_FIELD    = 4;
	public const TYPE_NEW      = 5;

	public static $rules = [
		'code_immo' => ['string', 'max:255'],
		'titleFR' => ['string', 'max:255'],
		'titleEN' => ['string', 'max:255'],
		'titleDE' => ['string', 'max:255'],
		'descriptionFR' => ['string', 'max:255'],
		'descriptionEN' => ['string', 'max:255'],
		'descriptionDE' => ['string', 'max:255'],
		'type_property' => ['integer', 'min:0'],
		'living_area' => ['integer', 'min:0'],
		'sejour_area' => ['integer', 'min:0'],
		'field_area' => ['integer', 'min:0'],
		'carrez_area' => ['integer', 'min:0'],
		'nb_room' => ['integer', 'min:0'],
		'nb_bathroom' => ['integer', 'min:0'],
		'nb_wc' => ['integer', 'min:0'],
		'nb_waterroom' => ['integer', 'min:0'],
		'nb_cave' => ['integer', 'min:0'],
		'bedroom' => ['integer', 'min:0'],
		'floor' => ['integer', 'min:0'], 
//		'address' => ['string', 'max:255'],
//		'address2' => ['string', 'max:255'],
		'zipcode' => ['string', 'max:255'],
		'city' => ['string', 'max:255'], 
		'country' => ['string', 'max:255'], 
		'price' => ['integer', 'min:0'], 
		'construction_year' => ['integer', 'min:0'],
		'date_offer' => ['date'],
		'date_updated_immo' => ['date'],
		'images_path' => ['string', 'max:255'],
		'thumbnail_path' => ['string', 'max:255'],
		'type' => ['string', 'max:255'],
		'favorite' => ['boolean'],
        'kitchen' => ['string', 'max:255'],
        'mitoyen' => ['string', 'max:255'],

        'standing' => ['string', 'max:255'],
        'type_parking' => ['string', 'max:255'],
        'interphone' => ['integer', 'min:0'],
        'garden' => ['integer', 'min:0'],
        'exterior_state' => ['string', 'max:255'],
        'bedroom_rdc' => ['integer', 'min:0'],
        'one_level' => ['integer', 'min:0'],
        'double_sejour' => ['integer', 'min:0'],
        'sanitation' => ['string', 'max:255'],
        'alarm' => ['string', 'max:255'],
        'new_old' => ['string', 'max:255'],
        'expo_sejour' => ['string', 'max:255'],

	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'code_immo' => 'string',
		'titleFR' => 'string',
		'titleEN' => 'string',
		'titleDE' => 'string',
		'descriptionFR' => 'string',
		'descriptionEN' => 'string',
		'descriptionDE' => 'string',
        'type_property' => 'integer',
        'living_area' => 'integer',
        'sejour_area' => 'integer',
        'field_area' => 'integer',
        'carrez_area' => 'integer',
        'nb_room' => 'integer',
        'nb_bathroom' => 'integer',
        'nb_wc' => 'integer',
        'nb_waterroom' => 'integer',
        'nb_cave' => 'integer',
		'bedroom' => 'integer', 
		'floor' => 'integer', 
//		'address' => 'string',
//		'address2' => 'string',
		'zipcode' => 'string',
		'city' => 'string', 
		'country' => 'string', 
		'price' => 'integer', 
		'construction_year' => 'integer',
		'date_offer' => 'datetime',
		'date_updated_immo' => 'datetime',
		'images_path' => 'string',
		'thumbnail_path' => 'string',
		'type' => 'string',
		'favorite' => 'boolean',
        'kitchen' => 'string',
        'mitoyen' => 'string',

        'standing' => 'string',
        'type_parking' => 'string',
        'interphone' => 'integer',
        'garden' => 'integer',
        'exterior_state' => 'string',
        'bedroom_rdc' => 'integer',
        'one_level' => 'integer',
        'double_sejour' => 'integer',
        'sanitation' => 'string',
        'alarm' => 'string',
        'new_old' => 'string',
        'expo_sejour' => 'string',
	];

	/******************************************************************************************************************
	/*********************************************** RELATIONS ********************************************************
	/*****************************************************************************************************************/

    /**
     * Favorites of property
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites(){
        return $this->hasMany(Favorite::class, 'property_id', 'id');
    }


    /**
     * Get users of property
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(){
        return $this->belongsToMany(User::class, 'favorites', 'property_id', 'user_id', 'id', 'id');
    }


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
            if(!empty($data['search']['zipcode']) ){
                if(!empty($data['search']['zone']) ){

                    // Get latitude and longitude of zipcode
                    $gps = City::getGPSCoordinate($data['search']['zipcode']);

                    // Get cities in selected area
                    $cities = City::getCitiesWithinDistance($gps['lat'],$gps['long'], $data['search']['zone']);

                    $builder->whereIn('zipcode', $cities->toArray() );

                } else{
                    $builder->where('zipcode', '=', $data['search']['zipcode']);
                }

                if( !empty($data['search']['maxprice']) ){
                    $builder->where('price', '<', $data['search']['maxprice']);
                }
            } elseif(!empty($data['search']['maxprice'])){

                $builder->where('price', '<', $data['search']['maxprice']);
            }

            $builder = $builder
                ->orderBy('date_updated_immo', 'DESC');


            return $builder;
		}

		return $builder;
	}


    /**
     * Get property by type
     * @param Builder $builder
     * @param $data
     * @return Builder
     */
	public function scopeGetByTypeProperty(Builder $builder, $data){
        if (!empty($data['search']['typeproperty'])){
            $builder->where('type_property', '=', $data['search']['typeproperty']);
        }
        return $builder;
    }


    /**
     * Get Favorites
     * @param Builder $builder
     * @param $data
     * @return Builder
     */
    public function scopeGetFavorites(Builder $builder){
        $builder->where('favorite', self::FAVORITE);
        return $builder;
    }


	/******************************************************************************************************************
	 * /*********************************************** FONCTIONS ********************************************************
	 * /*****************************************************************************************************************/

    /**
     * Return slug of property
     * @return string
     */
    public function getSlugAttribute(): string {
        return str_slug($this->getTitle());
    }


    /**
     * Get attribute of url
     * @return string
     */
    public function getUrlAttribute(): string {
        return action('FrontController@viewProperty', [$this->id, $this->slug]);
    }


    /**
     * Is property in favorite's list of specified user
     * @return bool
     */
    public function isFavoriteOfUser(){
        $userSpecified = $this->users()
            //->whereNull('favorites.deleted_at')
            ->wherePivot('deleted_at', null)
            ->where('user_id', '=', Auth::user()->id)
            ->first();

        if(isset($userSpecified)){
            return true;
        }
        return false;
    }


    /**
     * Get title
     * @return mixed
     */
    public function getTitle(){
        $lang = strtoupper($this->getCurrentLang());
        return $this->{'title'.$lang};
    }


    /**
     * Get description
     * @return mixed
     */
    public function getDescription(){
        $lang = strtoupper($this->getCurrentLang());
        return $this->{'description'.$lang};
    }


    /**
     * @param $image
     * @return array|bool
     */
    public function checkImageSize($image){
        $imgInfos = getimagesize($image);
        if($imgInfos[0] > 350 && $imgInfos[1] > 350){
            return true;
        }
        return false;
    }
}
