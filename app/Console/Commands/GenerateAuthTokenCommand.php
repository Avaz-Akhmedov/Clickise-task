<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateAuthTokenCommand extends Command
{
    protected $signature = 'generate:auth-token';


    protected $description = 'Generate auth token';


    public function handle(): void
    {
        $userId = $this->ask('Введите ID пользователя');

        $user = User::query()->find($userId);

        if (!$user) {
            $this->error('Пользователь не найден!');
            return;
        }

        if (!$this->confirm('Ваше имя: ' . $user->name . '. Это правильно?', true)) {
            $this->error('Имя пользователя не подтверждено. Прервано.');
            return;
        }

        $existingToken = $user->tokens()->first();

        if ($existingToken) {
            if ($this->confirm('У этого пользователя уже есть токен. Хотите ли вы его обновить?', false)) {
                $existingToken->delete();
            } else {
                $this->info('У пользователя уже есть токен: ' . $existingToken->plain_text_token);
            }
        }

        $newToken = $user->createToken('Auth Token')->plainTextToken;
        $this->info($newToken);
    }
}
