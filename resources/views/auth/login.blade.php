@extends('auth.layouts')

@section('content')
    <div class="row px-5" style="background: #eeeeee;">
        <div class="form-container">
            <div class="login py-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-7 col-lg-5 p-5">
                            <div class="login-wrap p-4 p-md-5 shadow-sm bg-light" style="border: 1px solid rgb(218, 218, 218); border-radius: 15px;">
                                <div class="icon d-flex align-items-center justify-content-center">
                                    <!-- Change below img tag to use the image from the project folder -->
                                    <img src="{{ asset('images/logo.png') }}" alt=""
                                        class="img-fluid d-block mx-auto mb-5" width="150" height="150">
                                </div>
                                {{-- <h5 class="text-center mb-4">JU MIS</h5> --}}
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" name="username"
                                            value="{{ old('username') }}" placeholder="Username">
                                        @if ($errors->has('username'))
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        @endif
                                        <label class="text-secondary" for="floatingInput">Username</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="floatingPassword" name="password"
                                            placeholder="Password">
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                        <label class="text-secondary" for="floatingPassword">Password</label>
                                    </div>

                                    <div class="d-grid">
                                        <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2"
                                            type="submit">Login</button>
                                        <div class="text-center">
                                            <a class="small" target="_blank" href="https://uas.ju.edu.et/login">Forgot
                                                password?</a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <br>
                            <div class="text-secondary text-center" style="display: flex; justify-content: space-between;">
                                <p style="text-decoration: underline;">
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script> - JU MIS
                                </p>

                                <p class=""><a href="{{ url('/') }}">HOME</a></p>
                            </div>

                        </div>
                    </div>
                </div>



            </div>
        </div>

    </div>
@endsection
