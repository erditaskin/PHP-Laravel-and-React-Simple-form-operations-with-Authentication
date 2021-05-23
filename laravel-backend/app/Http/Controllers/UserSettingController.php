<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSettingRequest;

use App\Models\UserSetting;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function userSettings(Request $request): \Illuminate\Http\JsonResponse
    {
        $setting = UserSetting::where('user_id', $request->user_id)->first();

        return response()->json([
            'code' => 200,
            'data' => $setting,
        ]);
    }

    /**
     * @param UserSettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userSettingsUpdate(UserSettingRequest $request): \Illuminate\Http\JsonResponse
    {
        /** @var UserSetting $checkSetting */
        $checkSetting = UserSetting::where('user_id', $request->user_id)->first();

        if (!$checkSetting) {
            $setting = new UserSetting();
            $setting->user_id = $request->user_id;
            $setting->max_amount = $request->get('maxAmount');
            $setting->save();

            return response()->json([
                'code' => 200,
                'data' => $setting,
            ]);
        }

        $checkSetting->max_amount = $request->get('maxAmount');
        $checkSetting->save();

        return response()->json([
            'code' => 200,
            'data' => $checkSetting,
        ]);
    }
}
