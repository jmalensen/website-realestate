<div class="bg-light">
	<ul class="nav-main d-flex nav-main-hover nav-main-dark px-2 m-0 justify-content-end">
		<li class="nav-main-item">
			@php
				$authUser = Auth::user();
			@endphp
			@if(!isset($authUser) )
				<a class="" href="{{ route('login') }}">@lang('commun.my_account')</a>
			@else
				<!-- User Dropdown -->
				<div class="dropdown d-inline-block">
					<button type="button" class="btn btn-dual py-0" id="page-header-user-dropdown" data-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-fw fa-user d-sm-none"></i>
						<span class="d-none d-sm-inline-block text-capitalize">{{$authUser->name }}</span>
						<i class="fa fa-fw fa-angle-down ml-1 d-sm-inline-block"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
						<div class="p-2">
							<a href="{{ route('users.profil', ['user' => $authUser->id]) }}" class="dropdown-item text-white">
								<i class="far fa-fw fa-user mr-1"></i> @lang('commun.my_profil')
							</a>
							<a href="{{ route('users.edit', ['user' => $authUser->id]) }}" class="dropdown-item text-white">
								<i class="far fa-fw fa-pen mr-1"></i> @lang('commun.edit')
							</a>
							@can('managepagesgrapesjs')
								<a href="{{ route('grapes.indexPage') }}" class="dropdown-item text-white">
									<i class="far fa-fw fa-sticky-note mr-1"></i> @lang('articles.articles')
								</a>
							@endcan
							<a href="{{ route('logout') }}" class="dropdown-item text-white"
							   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
								<i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> @lang('commun.logout')
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								{{ csrf_field() }}
							</form>
						</div>
					</div>
				</div>
				<!-- END User Dropdown -->
			@endif
		</li>
		<li class="nav-main-item">
			{{--------------Dropdown for language choice------------------}}
			<div class="dropdown d-inline-block">

				{{--If it's not a new user with a temporary password--}}
				@php
					if( !empty( $authUser->lang_preference ) ){
						$locale = $authUser->lang_preference;
					}
					else{
						$locale = \Illuminate\Support\Facades\Session::get('my_locale');
					}
				@endphp

				<button type="button"
						class="btn btn-dual py-0"
						id="menu-lang-dropdown"
						data-toggle="dropdown"
						aria-haspopup="true"
						aria-expanded="false">

					<span class="d-sm-inline-block">
						@switch($locale)
							@case('en')
							<span>EN</span>
							@break

							@case('fr')
							<span>FR</span>
							@break

							@default
							<span>FR</span>
							@break
						@endswitch
					</span>

					<i class="fa fa-fw fa-angle-down ml-1 d-sm-inline-block"></i>
				</button>

				<div class="dropdown-menu dropdown-menu-right p-0"
					 aria-labelledby="menu-lang-dropdown">

					<div class="p-2">
						<a class="dropdown-item text-white"
						   onclick="event.preventDefault();document.getElementById('langEn').submit();">
							<span class="language-select">English</span>
						</a>
						<form id="langEn" action="{{route('changeLocale', ['lang' => 'en', 'idpage' => (isset($idPage)) ? $idPage : null ])}}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>

						<div role="separator" class="dropdown-divider"></div>

						<a class="dropdown-item text-white"
						   onclick="event.preventDefault();document.getElementById('langFr').submit();">
							<span class="language-select">Français</span>
						</a>
						<form id="langFr" action="{{route('changeLocale', ['lang' => 'fr', 'idpage' => (isset($idPage)) ? $idPage : null])}}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</div>
				</div>
			</div>
			{{--------------End Dropdown for language choice------------------}}
		</li>
	</ul>
</div>
<div class="row d-lg-none pt-2 px-3">

	<div class="col-6 d-block d-lg-none">
		<a class="logo navbar-brand" href="{{ route('home') }}">
			<img src="{{ asset('images/logo-transparent-NB.png') }}" alt="{{ config('app.name', 'Laravel') }}">
		</a>
	</div>

	<div class="col-6 pt-3">
		<button type="button"
				class="btn btn-block btn-dark d-flex justify-content-end align-items-center"
				data-toggle="class-toggle"
				data-target="#contain-menu"
				data-class="d-none">
			@lang('commun.menu') &nbsp;&nbsp;&nbsp;
			<i class="fa fa-bars"></i>
		</button>
	</div>
</div>
{{--pt-2--}}
<div id="contain-menu" class="d-none d-lg-flex justify-content-between px-3 px-lg-0">
	<a class="logo d-none d-lg-block" href="{{ route('home') }}">
		<img src="{{ asset('images/logo-transparent-NB.png') }}" alt="{{ config('app.name', 'Laravel') }}">
	</a>
	<ul class="nav-main nav-main-horizontal nav-main-hover nav-main-dark pt-3 text-uppercase">
		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('home') ? ' active' : '' }}" href="{{ route('home') }}">
				<span class="nav-main-link-name">@lang('commun.home')</span>
			</a>
		</li>

		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('front.agency', ['slug' => str_slug( __('commun.agency') )]) ? ' active' : '' }}" href="{{route('front.agency', ['slug' => str_slug( __('commun.agency') )])}}">
				<span class="nav-main-link-name">@lang('commun.agency')</span>
			</a>
		</li>

		<li class="nav-main-item">
			<a class="nav-main-link nav-main-link-submenu{{ request()->routeIs('front.buy', ['slug' => str_slug( __('commun.buy') )]) ? ' active' : '' }}" href="{{route('front.buy', ['slug' => str_slug( __('commun.buy') )])}}" data-toggle="submenu" aria-haspopup="true" aria-expanded="false">
				<span class="nav-main-link-name">@lang('commun.buy')</span>
			</a>
			<ul class="nav-main-submenu">
				<li class="nav-main-item">
					<a class="nav-main-link{{ request()->routeIs('front.buyGuide', ['slug' => str_slug( __('commun.buy_guide') )]) ? ' active' : '' }}" href="{{route('front.buyGuide', ['slug' => str_slug( __('commun.buy_guide') )])}}">
						<span class="nav-main-link-name">@lang('commun.buy_guide')</span>
					</a>
				</li>
				<li class="nav-main-item">
					<a class="nav-main-link{{ request()->routeIs('front.sellToBuy', ['slug' => str_slug( __('commun.sell_to_buy') )]) ? ' active' : '' }}" href="{{route('front.sellToBuy', ['slug' => str_slug( __('commun.sell_to_buy') )])}}">
						<span class="nav-main-link-name">@lang('commun.sell_to_buy')</span>
					</a>
				</li>
			</ul>
		</li>

		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('front.sale', ['slug' => str_slug( __('commun.sale') )]) ? ' active' : '' }}" href="{{route('front.sale', ['slug' => str_slug( __('commun.sale') )])}}">
				<span class="nav-main-link-name">@lang('commun.sale')</span>
			</a>
		</li>

		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('front.evaluate', ['slug' => str_slug( __('commun.evaluate') )]) ? ' active' : '' }}" href="{{route('front.evaluate', ['slug' => str_slug( __('commun.evaluate') )])}}">
				<span class="nav-main-link-name">@lang('commun.evaluate')</span>
			</a>
		</li>

		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('front.news', ['slug' => str_slug( __('commun.news') )]) ? ' active' : '' }}" href="{{route('front.news', ['slug' => str_slug( __('commun.news') )])}}">
				<span class="nav-main-link-name">@lang('commun.news')</span>
			</a>
		</li>

		<li class="nav-main-item">
			<a class="nav-main-link{{ request()->routeIs('front.contact', ['slug' => str_slug( __('commun.contact') )]) ? ' active' : '' }}" href="{{route('front.contact', ['slug' => str_slug( __('commun.contact') )])}}">
				<span class="nav-main-link-name">@lang('commun.contact')</span>
			</a>
		</li>

	</ul>
</div>