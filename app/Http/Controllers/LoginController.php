<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
   function showloginForm(): View
   {
        return view('logins.form');
   }

   function logout(): RedirectResponse 
   {
    Auth::logout();
    session()->invalidate();
    
    // regenerate CSRF token
    session()->regenerateToken();
    
    return redirect()->route('login');
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse 
    {
        // get credentials from user.
        $data = $request->getParsedBody();
        $credentials =[
            'email' => $data['email'],
            'password' => $data['password'],
        ];
        
        // authenticate by using method attempt()
        if(Auth::attempt($credentials)){
            //regenerate the new session ID
            session()->regenerate();

            //
            //
            return redirect()->intended(route('products.list'));
        }

        // if cannot authenticate redirect back to loginForm with error message.
        return redirect()->back()->withErrors([
            'credentials' => 'the provided credentials do not match our record. ',
        ]);
        }
}
