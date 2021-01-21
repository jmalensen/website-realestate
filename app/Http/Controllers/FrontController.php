<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Requests\EvaluateRequest;
use App\Http\Requests\RecruitRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\SubscriberRequest;
use App\Mail\ContactMail;
use App\Mail\EvaluateMail;
use App\Mail\RecruitMail;
use App\Models\Favorite;
use App\Models\Page;
use App\Models\Property;
use App\Models\Subscriber;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class FrontController extends Controller
{
    /**
     * Change Locale of application
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLocale(Request $request){

        //If lang is in language of app
        if (in_array($request->lang, config('app.languages'))) {;
            $request->session()->put('my_locale', $request->lang);

            $authUser = Auth::user();
            if(!empty($authUser) ){
                $authUser->lang_preference = $request->lang;
                $authUser->save();
            }
        }

        // If this a specific page
        if($request->idpage != null){
            $page = Page::find($request->idpage);
            return redirect()->route($page->name_path, ['slug' => $page->getSlugAttribute()]);
        }
        return redirect()->back();
    }


    /**
     * Home page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $properties = Property::getFavorites()->limit(5)->get();

        $authUser = Auth::user();
        if(isset($authUser) ){
            $hasAuthUser = true;
        } else{
            $hasAuthUser = false;
        }

        return view('front.home', compact('properties', 'hasAuthUser'));
    }


    /**
     * Agency page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function agency(Request $request){
        $idPage = 1;
        return view('front.agency', compact('idPage'));
    }


    /**
     * Buy page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buy(Request $request){
        $idPage = 2;
        return view('front.buy', compact('idPage'));
    }

    /**
     * Buyer testimonies page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buyerTestimonies(Request $request){
        $idPage = 3;
        return view('front.buyer-testimonies', compact('idPage'));
    }

    /**
     * Area guide page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function areaGuide(Request $request){
        $idPage = 4;
        return view('front.area-guide', compact('idPage'));
    }

    /**
     * Buy guide page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buyGuide(Request $request){
        $idPage = 5;
        return view('front.buy-guide', compact('idPage'));
    }

    /**
     * Buy page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sellToBuy(Request $request){
        $idPage = 6;
        return view('front.sell-to-buy', compact('idPage'));
    }


    /**
     * Subscribe to offers
     * @param SubscriberRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribeOffers(SubscriberRequest $request){
        $data = $request->validated();

        $subscriber = new Subscriber();
        $subscriber->email = $data['email'];
        $subscriber->save();

        return redirect()->route('front.buy');
    }


    /**
     * Sale page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sale(Request $request){
        $idPage = 7;
        return view('front.sale', compact('idPage'));
    }


    /**
     * View list properties page
     * @param SearchRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewListProperties(SearchRequest $request){

        $data = $request->only(['search']);

        $authUser = Auth::user();
        if(isset($authUser) ){
            $hasAuthUser = true;
        } else{
            $hasAuthUser = false;
        }

        $properties = Property::sortable()
            ->resetPaginate($data);

        // If we want specific property type
        if(!empty($data['search']) && !empty($data['search']['typeproperty'])){
            $properties = $properties->getByTypeProperty($data);
        }

        $properties = $properties->paginate(10);

        $data = (isset($data['search'])) ? $data['search'] : null;

        return view('front.index-properties', compact('properties', 'data', 'hasAuthUser'));
    }


    /**
     * View property page
     * @param Request $request
     * @param Property $property
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewProperty(Request $request, Property $property){

        $storagePath = Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix();
        $localDir = $storagePath.$property->images_path; // => ".../imagesProperties/<1>/"
        $images = glob($localDir . "*.jpg");
        $pattern = '/'.preg_quote($storagePath, '/').'/';

        // Prepare images for view
        foreach($images as $key => $image){
            // Check if image is big enough to display on page
            if( $property->checkImageSize($image) ){
                $images[$key] = preg_replace( $pattern , 'storage/', $image);
            } else{
                unset($images[$key]);
            }
        }

        return view('front.property', compact('property', 'images'));
    }


    /**
     * Evaluate page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function evaluate(Request $request){
        $idPage = 8;
        return view('front.evaluate', compact('idPage'));
    }


    /**
     * Handle evaluate form data
     * @param EvaluateRequest $request
     * @return string
     */
    public function evaluateSent(EvaluateRequest $request){
        $data = $request->validated();

        try{
            Mail::to('contact@theoucafeimmo.com')
                ->send(new EvaluateMail($data));
        } catch(Exception $ex){
            return $ex->getMessage();
        }

        return redirect()->route('front.evaluate');
    }


    /**
     * News page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news(Request $request){
        $articles = PageGrapesjs::sortable()
            ->resetPaginate($request->only(['search']))
            ->pagesBlogPublished()
            ->orderBy('date_publish', 'DESC')
            ->paginate(10);

        $idPage = 9;

        return view('front.news', compact('articles', 'idPage'));
    }


    /**
     * View article page
     * @param Request $request
     * @param PageGrapesjs $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewArticle(Request $request, $date, $slug){
        $page = PageGrapesjs::whereSlug($slug)->first();
        return view('front.articles.article', compact('page'));
    }


    /**
     * Network page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function network(Request $request){
        $idPage = 10;
        return view('front.network', compact('idPage'));
    }


    /**
     * Recruit page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recruit(Request $request){
        $idPage = 11;
        return view('front.recruit', compact('idPage'));
    }


    /**
     * Handle recruit form data
     * @param RecruitRequest $request
     * @return string
     */
    public function recruitSent(RecruitRequest $request){
        $data = $request->validated();

        try{
            Mail::to('contact@theoucafeimmo.com')
                ->send(new RecruitMail($data));
        } catch(Exception $ex){
            return $ex->getMessage();
        }

        return redirect()->route('front.recruit');
    }


    /**
     * Contact page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact(Request $request){
        $idPage = 12;
        return view('front.contact', compact('idPage'));
    }


    /**
     * Handle contact form data
     * @param ContactRequest $request
     * @return string
     */
    public function contactSent(ContactRequest $request){
        $data = $request->validated();

        try{
            Mail::to('contact@theoucafeimmo.com')
                ->send(new ContactMail($data));
        } catch(Exception $ex){
            return $ex->getMessage();
        }

        return redirect()->route('front.contact');
    }


    /**
     * Legal notice page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function legalNotice(Request $request){
        $idPage = 13;
        return view('front.legal-notice', compact('idPage'));
    }


    /**
     * Privacy policy page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacyPolicy(Request $request){
        $idPage = 14;
        return view('front.privacy-policy', compact('idPage'));
    }


    /**
     * Change favorite for authUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function changeFavoriteUserAjax(Request $request){
        $this->isAjaxOrFail($request);
        $data = $request->all();
        if(!isset($data['property_id'])){
            return $this->ajaxDataMissing();
        }

        $authUser = \Auth::user();
        $checkFavorite = Favorite::where('user_id', $authUser->id)
            ->where('property_id', $data['property_id'])
            ->first();

        // If there is no favorite with these ids
        if(!isset($checkFavorite)){
            $favorite = new Favorite();
            $favorite->user_id = $authUser->id;

            $property = Property::find($data['property_id']);
            $favorite->property_id = $property->id;

            if($favorite->save()){
                return response()->json(['success' => true,'data' => $data, 'message' => __('Ajouté aux favoris')]);
            }
        } else{
            if($checkFavorite->delete() ){
                return response()->json(['success' => true,'data' => $data, 'message' => __('Supprimé des favoris')]);
            }
        }

        return $this->jsonError( __('Erreur pendant la sauvegarde') );
    }
}
