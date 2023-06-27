@extends('auth.layouts')

@section('content')
    {{-- <div class="row justify-content-center mt-5">
    <div class="col-md-4">

        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-info" value="Login">
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">Management information systems (MIS)</div>
            <div class="card-body">
                 <p>
                    Management information systems (MIS) is the study and application of information systems that organizations use for data access, management, and analytics.
                </p>
                <p>
                    An MIS is a system that provides managers with the necessary information to make decisions about an organization's operations. The MIS gathers data from various sources and processes it to provide information tailored to the managers' and their staff's needs.
                    <p>
                        While businesses use different types of MISs, they all share one common goal: to provide managers with the information to make better decisions. In today's fast-paced business environment, having access to accurate and timely information is critical for success. MIS allows managers to track performance indicators, identify trends, and make informed decisions about where to allocate resources.


                </p>
                <h5>Importance of management information systems for businesses</h5>
                <p>
                    MISs allow businesses to have access to accurate data and powerful analytical tools to identify problems and opportunities quickly and make decisions accordingly. A management information system should do the following:

                </p>
                <p>

                Provide you with information you need to make decisions

                Can give you a competitive edge by providing timely, accurate information

                Can help you improve operational efficiency and productivity

                Allows you to keep track of customer activity and preferences

                Enables you to develop targeted marketing campaigns and improve customer service
                </p>
            </div>
        </div>
    </div>
</div> --}}

    <div class="row px-5">

        <div class="col-lg-4 col-md-6 form-container">
            <div class="login d-flex align-items-center py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9 col-lg-8 mx-auto">
                            <h3 class="login-heading mb-4">Login</h3>

                            <!-- Sign In Form -->
                            <form action="{{ route('login') }}" method="post">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput" name="email"
                                        value="{{ old('email') }}" placeholder="Username">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
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
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-6 d-none d-md-block m-auto">
            <div class="p-5 m-auto border rounded">
                <h4>MIS (Management Information System)</h4>
                <p>
                    Management information systems (MIS) is the study and application of information systems that
                    organizations use for data access, management, and analytics.
                </p>
                <p>
                    An MIS is a system that provides managers with the necessary information to make decisions about an
                    organization's operations. The MIS gathers data from various sources and processes it to provide
                    information tailored to the managers' and their staff's needs.
                <p>
                    While businesses use different types of MISs, they all share one common goal: to provide managers with
                    the information to make better decisions. In today's fast-paced business environment, having access to
                    accurate and timely information is critical for success. MIS allows managers to track performance
                    indicators, identify trends, and make informed decisions about where to allocate resources.


                </p>
                <h5>Importance of management information systems for businesses</h5>
                <p>
                    MISs allow businesses to have access to accurate data and powerful analytical tools to identify problems
                    and opportunities quickly and make decisions accordingly. A management information system should do the
                    following:

                </p>
                <p>

                    Provide you with information you need to make decisions

                    Can give you a competitive edge by providing timely, accurate information

                    Can help you improve operational efficiency and productivity

                    Allows you to keep track of customer activity and preferences

                    Enables you to develop targeted marketing campaigns and improve customer service
                </p>
            </div>
        </div>

    </div>
@endsection
