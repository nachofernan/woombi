<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramController extends Controller
{
    public function generarToken(Request $request)
    {
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $request->user()->update([
            'telegram_token'            => $token,
            'telegram_token_expires_at' => now()->addMinutes(2),
        ]);

        return response()->json([
            'token'      => $token,
            'expires_at' => now()->addMinutes(2),
        ]);
    }

    public function webhook(Request $request, TelegramService $telegram)
    {
        $message = $request->input('message');

        if (!$message) return response()->json(['ok' => true]);

        $chatId = $message['chat']['id'];
        $text   = trim($message['text'] ?? '');

        if (str_starts_with($text, '/start')) {
            $telegram->sendMessage($chatId,
                "👋 <b>Bienvenido al bot de Woombi!</b>\n\n" .
                "Para vincular tu cuenta, ingresá a la app, generá tu código y mandame:\n\n" .
                "<code>/vincular 123456</code>"
            );
            return response()->json(['ok' => true]);
        }

        if (str_starts_with($text, '/vincular')) {
            $parts = explode(' ', $text);
            $code  = $parts[1] ?? null;

            if (!$code) {
                $telegram->sendMessage($chatId, '⚠️ Usá el formato: <code>/vincular 123456</code>');
                return response()->json(['ok' => true]);
            }

            $user = User::where('telegram_token', $code)
                ->where('telegram_token_expires_at', '>', now())
                ->first();

            if (!$user) {
                $telegram->sendMessage($chatId, '❌ Código inválido o expirado. Generá uno nuevo desde la app.');
                return response()->json(['ok' => true]);
            }

            $user->update([
                'telegram_chat_id'          => $chatId,
                'telegram_token'            => null,
                'telegram_token_expires_at' => null,
            ]);

            $telegram->sendMessage($chatId, "✅ <b>¡Cuenta vinculada!</b> A partir de ahora te voy a avisar los resultados de los partidos.");
            $telegram->sendMessage($chatId, "<b>Pibeeeeeeee!! Tenemos Bot!</b> 🤖");
            $telegram->sendMessage($chatId, "Ahora andá a la administración y agregá un resultado para probar. 🏆");
            $telegram->sendMessage($chatId, "Acordate que era en https://woombi.elbondi.online/ y usá a admin@prode.com -> password para entrar. 🎯");
            $telegram->sendMessage($chatId, "Besis 🥰");
            $telegram->sendMessage($chatId, "Pd: acordate de poner el partido como \"finalizado\" 🤫");
            return response()->json(['ok' => true]);
        }

        // TODO: agregar mas comandos
        if (str_starts_with($text, '/desvincular')) {
            $this->desvincular($request);
            $telegram->sendMessage($chatId, "✅ <b>¡Cuenta desvinculada!</b> A partir de ahora no te voy a avisar los resultados de los partidos.");
            return response()->json(['ok' => true]);
        }

        // TODO: agregar mas comandos
        if (str_starts_with($text, '/puntos')) {
            $user = User::where('telegram_chat_id', $chatId)->first();
            if (!$user) {
                $telegram->sendMessage($chatId, '❌ No tienes una cuenta de Telegram vinculada.');
                return response()->json(['ok' => true]);
            }
            $telegram->sendMessage($chatId, "✅ <b>¡Puntos!</b>");
            $telegram->sendMessage($chatId, "Tu cuenta tiene {$user->total_points} puntos");
            return response()->json(['ok' => true]);
        }

        $user = User::where('telegram_chat_id', $chatId)->first();
        if (!$user) {
            $telegram->sendMessage($chatId,
                " 🤖 Para vincular tu cuenta, ingresá a la app, generá tu código y mandame:\n\n" .
                "<code>/vincular 123456</code>"
            );
        } else {
            $telegram->sendMessage($chatId, 
                "Hola {$user->name}, ¿en qué te puedo ayudar? \n\n" .
                "Podes usar los siguientes comandos:\n\n" .
                "/puntos - Ver mis puntos\n\n" .
                "/desvincular - Desvincular mi cuenta de Telegram"
            );
        }

        return response()->json(['ok' => true]);
    }

    public function desvincular(Request $request)
    {
        $request->user()->update([
            'telegram_chat_id' => null,
        ]);

        return response()->json(['message' => 'Cuenta de Telegram desvinculada']);
    }
}