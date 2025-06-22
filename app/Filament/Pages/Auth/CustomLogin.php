<?php
Namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Log;

class CustomLogin extends BaseLogin
{

    /**
     * @var view-string
     */
    protected static string $view = 'filament.pages.auth.custom-login';
  

}

