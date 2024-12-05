<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmSettingUpdateRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Http\Requests\UpdateUserSettingRequest;
use App\Http\Resources\UserSettingResource;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSmsJob;
use App\Models\UserSetting;
use App\Models\VerificationCode;
use App\Services\SmsService;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Twilio\Rest\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{


    public function index(): ResourceCollection
    {
        $user = Auth::user();
        $settings = $user->settings()->latest()->paginate(10);

        return UserSettingResource::collection($settings);
    }

    public function requestUpdate(UpdateUserSettingRequest $request): JsonResponse
    {
        $user = Auth::user();

        $unexpiredCode = VerificationCode::unexpired($user->id, $request->input('setting_id'))
            ->latest()
            ->first();

        if ($unexpiredCode) {
            return response()->json([
                'message' => 'У вас есть действующий код, используйте его или повторите попытку позже.',
            ], 400);
        }


        $code = VerificationCode::generate(
            $request->input('setting_id'),
            $request->input('setting_value'),
        );
        $message = "Это ваш код подтверждения, чтобы изменить настройки: $code";


        if ($request->input('method') == 'sms') {
            SendSmsJob::dispatch($user->phone_number, $message);
        } else {
            SendEmailJob::dispatch($user, $message);
        }


        return response()->json([
            'message' => 'Код отправлен'
        ]);
    }

    public function confirmUpdate(ConfirmSettingUpdateRequest $request): JsonResponse
    {

        $user = Auth::user();

        $verification = VerificationCode::query()
            ->whereHas('setting', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->first();


        if (!$verification) {
            return response()->json([
                'message' => 'Код подтверждения не найден.'
            ], 404);
        }

        if ($verification->code !== $request->input('code')) {
            return response()->json([
                'message' => 'Неправильный код.'
            ], 400);
        }

        if ($verification->expires_at < now()) {
            return response()->json([
                'message' => 'Срок действия вашего кода истек.'
            ], 400);
        }

        $verification->setting->update([
            'setting_value' => $verification->new_setting_value,
        ]);

        $verification->delete();

        return response()->json([
            'message' => 'Ваши настройки обновлены'
        ]);
    }



}
