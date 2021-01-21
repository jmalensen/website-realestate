<?php

namespace App\Http\Requests;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$username = User::$rules['username'];
		if ($this->has('id')) {
			$index = array_search('unique:users,username', $username);
			if (FALSE !== $index) {
				$username[$index] .= ',' . $this->input('id');
			} else {
				$username[] = 'unique:users,username,' . $this->input('id');
			}
		}
		$password = User::$rules['password'];
		if ($this->has('id')) {
			$password = '';
		}
		return [
			'firstname' => User::$rules['firstname'],
			'name' => User::$rules['name'],
			'email' => User::$rules['email'],
			'username' => $username,
			'password' => $password,

            'gender' => User::$rules['gender'],
			'phone' => User::$rules['phone'],
			'address' => User::$rules['address'],
			'zipcode' => User::$rules['zipcode'],
			'city' => User::$rules['city'],
			'country' => User::$rules['country'],
			'newsletter' => User::$rules['newsletter'],
		];
	}
}
