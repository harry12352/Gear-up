<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SecuritySettingsController extends SettingsController
{
    protected $view = "settings.security";

    protected $validationRules = [
            'old_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    
    public function saveSettings($userInput)
    {
        // Update password settings.
        $this->updatePassword($userInput);

        return redirect(route('settings.security'))->with("success", "Password updated successfully.");
    }
    
    protected function updatePassword($userInput) {
        $user = Auth::user();
        $oldPassword = $userInput["old_password"] ?? null;
        $newPassword = $userInput["password"] ?? null;

        if ($oldPassword && Hash::check($oldPassword, $user->password)) {
            if (!$newPassword) {
                throw ValidationException::withMessages([
                    "password" => "New password is required to update password."
                ]);
            }
            $user->password = Hash::make($newPassword);
            return $user->save();
        }
        elseif ($oldPassword) {    
            throw ValidationException::withMessages([
                "old_password" => "Password is incorrect"
            ]);
        }
        else {
            throw ValidationException::withMessages([
                "old_password" => "Password is required to change password."
            ]);
        }
    }
}
