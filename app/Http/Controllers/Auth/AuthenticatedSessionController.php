<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->input();

        // Finding a user:
        //$user = Adldap::search()->users()->find('john doe');
        //dd($user);
        $ldp_login =true;
        if($ldp_login){
            $this->authenticate($credentials);

        }
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

 public static function authenticate($credentials)
    {
        $url = config('parameters.url');
        $ldap_port = config('parameters.ldap_port');
        $LDAP_BASE_DN = config('parameters.LDAP_BASE_DN');
        $ldapconn = ldap_connect($url, $ldap_port);
        $uid = $credentials['username'];
        $password = $credentials['password'];
        try {
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            $ldapbind = ldap_bind($ldapconn, "uid=$uid,ou=people,$LDAP_BASE_DN", $password);
            //dd($ldapbind);
            //true if credentials are valid
            if ($ldapbind) {
                $search = ldap_search($ldapconn, $LDAP_BASE_DN, "uid=$uid");
                $info = ldap_get_entries($ldapconn, $search);

                // if ($info[0]['employeetype']['count'] > 0 && $info[0]['employeetype'][0] == 'Student')
                //     return new UnauthorizedException(403);

                $first_name = $info[0]['givenname'][0];
                $middle_name = 'Unknown';
                $last_name = 'Unknown';
                if (isset($info[0]['sn'])) {
                    if ($info[0]['sn']['count'] > 0) {
                        $middle_name = $info[0]['sn'][0];
                    }
                }
                if (isset($info[0]['sn'])) {
                    if ($info[0]['sn']['count'] > 1) {
                        $last_name = $info[0]['sn'][1];
                    }
                }
                $email = 'Unknown';
                if (isset($info[0]['mail'])) {
                    if ($info[0]['mail']['count'] > 0) {
                        $email = $info[0]['mail'][0];
                    }
                }
                $phone = 'Unknown';
                if (isset($info[0]['mobile'])) {
                    if ($info[0]['mobile']['count'] > 0) {
                        $phone = $info[0]['mobile'][0];
                    }
                }
                if (isset($info[0]['homephone'])) {
                    if ($info[0]['homephone']['count'] > 0) {
                        $phone = $info[0]['homephone'][0];
                    }
                }
                $user = User::where('username', '=', $credentials['username'])->first();
                if (!$user) {
                    try {
                        $user = User::create([
                            'username' => $uid,
                            'password' => Hash::make($password),
                            'name' => $first_name." ".$middle_name." ".$last_name,
                            'email' => $email,
                            // 'gender' => 'Unknown',
                            // 'phone' => $phone,

                        ]);

                        // ]);
                        // $user->assignRole('Staff');

                        return $user;
                    } catch (Exception $e) {
                        return $e;
                    }
                } else {
                    // dd($first_name,$middle_name,$last_name,$email,$phone);

                    $user->update([
                        'username' => $uid,
                        'password' => Hash::make($password),
                        'name' => $first_name." ".$middle_name." ".$last_name,
                        'email' => $email,
                        // 'gender' => 'Unknown',
                        // 'phone' => $phone,
                ]);

                 $user->save();
                    return $user;
                }
            } else {
                return new ModelNotFoundException();
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Invalid credentials') == true) {
                if (Config::get('app.env') == 'local') {
                    // dd('stop');
                    return Auth::attempt(['email' => $uid, 'password' => $password]) ? Auth::user() : new ModelNotFoundException();
                }
                return new ModelNotFoundException();
            } else {
                if (Auth::attempt(['email' => $uid, 'password' => $password])) {
                    return Auth::user();
                } else {
                    return new ModelNotFoundException();
                }
            }
        }
    }

}
