<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SettingsController extends Controller
{

    protected $view = "";

    protected $validationRules = [];

    public function __construct()
    {
        $this->middleware("auth");
    }

    public function handleSettings(Request $request)
    {
        if ($request->isMethod('POST')) {
            return $this->saveSettings($request->validate($this->validationRules));
        }
        return $this->showSettingsForm();
    }

    public function showSettingsForm()
    {
        $data = [
            "title" => "Account Settings",
            "tab" => "account",
            "user" => Auth::user()
        ];
        return view($this->view, $data);
    }
}
