<nav class="navbar navbar-expand navbar-light navbar-info" style="background: #3074a4;">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i
                    class="fas fa-bars"></i></a>
        </li>

    </ul>

    <ul class="navbar-nav ml-auto mr-3">

        <li class="nav-item mr-3">
            <span class="content-header">
                <select class="changeLang" style="">
                    <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English</option>
                    <option value="am" {{ session()->get('locale') == 'am' ? 'selected' : '' }}>Amharic</option>
                    <option value="oro" {{ session()->get('locale') == 'oro' ? 'selected' : '' }}>Oromifa</option>
                </select>
            </span>
        </li>

        <li class="nav-item dropdown">
            <a class="dropdown-toggle text-white" data-toggle="dropdown" href="#">
                <span class="hidden-xs glyphicon glyphicon-user text-white">
                    {{ Auth::user()->name }}
                    @if (!Auth::user()->offices->isEmpty())
                        {{ '(' }}{{ Auth::user()->offices[0]->officetranslations[0]->name }}{{ ')' }}
                    @endif
                </span>

            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                {{-- <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <div class="dropdown-divider"></div> --}}

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="dropdown-item">
                        <i class="nav-icon icon ion-md-exit"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

            </ul>
        </li>


    </ul>
</nav>
