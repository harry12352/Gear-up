<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FileStorage;
use Illuminate\Support\Facades\DB;
use App\Models\File;


class AccountSettingsController extends SettingsController
{
    protected $view = "settings.account";

    protected $validationRules = [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
        'bio' => ['nullable', 'string'],
        'phone' => ['nullable', 'integer'],
        'profile_image' => ['nullable', 'file', "mimes:jpeg,jpg,png"],
        'old_password' => ['nullable', 'string'],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ];

    public function saveSettings($userInput) {
        $user = Auth::user();
        // Updating name.
        $user->first_name = $userInput["first_name"];
        $user->last_name = $userInput["last_name"];

        // Updating email.
        if ($userInput['email'] != $user->email) {
            $user->email = $userInput['email'];
        }

        if (isset($userInput['bio'])) {
            $user->bio = $userInput['bio'];
        }

        if (isset($userInput['phone'])) {
            $user->phone = $userInput['phone'];
        }

        return $this->saveNewSettings($user, $userInput);
    }

    protected function updateProfileImage($user, $newImage, $oldImageID) {
        $oldProfileImage = null;
        if (!$newImage) {
            return true;
        }
        $path = FileStorage::store($newImage, "images");

        // Failed to upload image in the storage.
        if (!$path) {
            return false;
        }
        $profileImage = new File([
            "path" => $path,
            "resource_id" => $user->id,
            'resource_name'=>'user',
        ]);
        if ($oldImageID) {
            $oldProfileImage = File::find($oldImageID);
            // Deleting the old image.
            FileStorage::delete($oldProfileImage->path);
        }

        return ["oldImage" => $oldProfileImage, "newImage" => $profileImage];
    }

    protected function saveNewSettings($user, $userInput) {
        if (isset($userInput["profile_image"])) {
            // Updating user profile image.
            $imageFiles = $this->updateProfileImage($user, $userInput["profile_image"], $user->profile_picture);
            // If failed to update image returning error.
            if (!$imageFiles) {
                session()->flash("error", "Failed to update profile image.");
                return redirect(route('settings.account'));
            }
            $profileImage = $imageFiles["newImage"];
            $oldProfileImage = $imageFiles["oldImage"];

            // Uploading database records in transaction of related data.
            DB::transaction(function () use ($user, $profileImage, $oldProfileImage) {
                $profileImage->save();
                $user->profile_picture = $profileImage->id;
                $user->save();
                if ($oldProfileImage) {
                    $oldProfileImage->delete();
                }
            });

        } else {
            $user->save();
        }

        session()->flash("success", "Settings updated successfully.");

        return redirect(route('settings.account'));
    }

}
