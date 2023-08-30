<nav class="navbar navbar-expand navbar-light navbar-info" style="background: #3074a4;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="icon ion-md-menu"></i>
                </a>
            </li>
        </ul>
          <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">        
     
        </ul>
      </div>



        <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
              <span class="content-header" >  
                  <select class="changeLang" style="margin-top: 15px;">
                      <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English</option>
                      <option value="am" {{ session()->get('locale') == 'am' ? 'selected' : '' }}>Amharic</option>
                      <option value="oro" {{ session()->get('locale') == 'oro' ? 'selected' : '' }}>Oromifa</option>
                  </select> 
              </span>
          </li>
        
            @guest
             <li class="nav-item">
                  <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}" href="{{ route('login') }}"  style="color: white;">Login</a>
              </li>
             @else  
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"style="color:white;">
              <span class="hidden-xs glyphicon glyphicon-user ">   
                     {{ Auth::user()->name }}
                     @if(!Auth::user()->offices->isEmpty())
                         {{"("}} {{Auth::user()->offices[0]->officetranslations[0]->name}}{{")"}}
                      @endif
                     </span>
            </a>
            <ul class="dropdown-menu">
               
              <li class="user-footer">
                <div class="pull-right">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="btn btn-default btn-flat">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
              </li>
            </ul>
          </li>
           @endguest
     
        </ul>
      </div>

         
    </div>
</nav>
