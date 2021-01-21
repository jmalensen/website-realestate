<?php

namespace App\Policies;
use App\Models\Page;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy {
	use HandlesAuthorization;
	
	public function create(User $connecteduser) {
        if( $connecteduser->isSuperAdmin() ){
            return true;
        }
        return false;
	}
	
	public function edit(User $connecteduser, Page $page) {
        if( $connecteduser->isSuperAdmin() ){
            return true;
        }
        return false;
	}
	

	/**
	* si $page est null; on est dans la vue index
	*/
	public function view(User $connecteduser, Page $page = null) {
        if( $connecteduser->isSuperAdmin() ){
            return true;
        }
        return false;
	}
	
	
	public function delete(User $connecteduser, Page $page) {
        if( $connecteduser->isSuperAdmin() ){
            return true;
        }
        return false;
	}
}
