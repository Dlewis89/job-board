<?php
namespace App\Utils;
use App\Models\User;
use Illuminate\Support\Str;
class Util {
    public static function generate_referral_code() {
        $referral_code = "";
        do {
            $referral_code = Str::random(5);
        } while(User::where('referral_code', $referral_code)->exists());
        return $referral_code;
    }
}
