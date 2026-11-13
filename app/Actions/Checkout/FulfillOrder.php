<?php

namespace App\Actions\Checkout;

use App\Actions\Courses\GrantCourseAccess;
use App\Mail\OrderApprovedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Entrega um pedido pago: encontra (ou cria) a conta do comprador,
 * libera o curso por 1 ano e envia o e-mail de confirmação.
 * Idempotente: webhook e resposta síncrona podem chegar juntos.
 */
class FulfillOrder
{
    public const SOURCE = 'mercadopago';

    public function __construct(private readonly GrantCourseAccess $grantCourseAccess) {}

    public function handle(Order $order): void
    {
        $result = DB::transaction(function () use ($order) {
            $locked = Order::query()->lockForUpdate()->findOrFail($order->id);

            if ($locked->paid_at !== null) {
                return null;
            }

            $user = $locked->user ?? User::withTrashed()->firstWhere('email', $locked->email);
            $isNewUser = false;

            if (! $user) {
                $user = User::query()->create([
                    'name' => $locked->name,
                    'email' => $locked->email,
                    'password' => Str::password(32),
                    'role' => 'student',
                ]);
                $isNewUser = true;
            } elseif ($user->trashed()) {
                $user->restore();
            }

            $this->grantCourseAccess->handle($user, $locked->course, self::SOURCE, now()->addYear());

            $locked->forceFill(['user_id' => $user->id, 'paid_at' => now()])->save();

            return [$locked, $user, $isNewUser];
        });

        if ($result === null) {
            return;
        }

        [$order, $user, $isNewUser] = $result;

        // Conta criada no checkout: o link de "criar senha" usa o fluxo de redefinição.
        $passwordToken = $isNewUser ? Password::createToken($user) : null;

        Mail::to($order->email)->queue(new OrderApprovedMail($order, $passwordToken));
    }
}
