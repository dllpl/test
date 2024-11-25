<?php

namespace extras\plugins\balance;

use App\Helpers\Number;
use App\Models\Post;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Helpers\Payment;
use App\Models\Package;

class Balance extends Payment
{
    /**
     * Send Payment
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @param array $resData
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public static function sendPayment(Request $request, Post $post, array $resData = [])
    {
        // Set the right URLs
        parent::setRightUrls($resData);

        // Get the Package
        $package = Package::find($request->input('package_id'));

        // Don't make a payment if 'price' = 0 or null
        if (empty($package) || $package->price <= 0) {
            return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
        }

        // Get the amount
        $amount = Number::toFloat($package->price);

        // Check user's balance
        $userBalance = auth()->user()->balance; // Предполагается, что у пользователя есть поле balance

        if ($userBalance < $amount) {
            return redirect(parent::$uri['previousUrl'] . '?error=insufficient_balance')->withInput();
        }

        // Deduct the amount from user's balance
        auth()->user()->decrement('balance', $amount);

        // Optionally, you can log the transaction here
        // Transaction::create(['user_id' => auth()->id(), 'amount' => -$amount, 'description' => 'Payment for package ' . $package->name]);

        // Redirect to success page or perform other actions
        return redirect(parent::$uri['paymentReturnUrl']);
    }

    /**
     * @param $params
     * @param $post
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public static function paymentConfirmation($params, $post)
    {
        // Handle payment confirmation actions if necessary
        return parent::paymentConfirmationActions($params, $post);
    }

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        $options = [];

        $paymentMethod = PaymentMethod::active()->where('name', 'balance')->first();
        if (!empty($paymentMethod)) {
            $options[] = (object)[
                'name'     => mb_ucfirst(trans('admin.settings')),
                'url'      => admin_url('payment_methods/' . $paymentMethod->id . '/edit'),
                'btnClass' => 'btn-info',
            ];
        }

        return $options;
    }

    /**
     * @return bool
     */
    public static function installed(): bool
    {
        $cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)

        return cache()->remember('plugins.balance.installed', $cacheExpiration, function () {
            $paymentMethod = PaymentMethod::active()->where('name', 'balance')->first();
            return !empty($paymentMethod);
        });
    }

    /**
     * @return bool
     */
    public static function install(): bool
{
    // Удалите предыдущий экземпляр плагина, если есть
    self::uninstall();

    // Данные плагина
    $data = [
        'name'              => 'balance',
        'display_name'      => 'Balance method',
        'description'       => 'Balance method',
        'has_ccbox'         => 0,
        'is_compatible_api' => 0,
        'countries'         => 'ru',
        'lft'               => 0,
        'rgt'               => 0,
        'depth'             => 1,
        'parent_id'         => 0,
        'active'            => 1,
    ];

    try {
        // Создайте данные плагина
        $paymentMethod = PaymentMethod::create($data);
        return !empty($paymentMethod);
    } catch (\Throwable $e) {
        // Логирование ошибки
        \Log::error('Ошибка установки плагина "Списание с баланса": ' . $e->getMessage());
        return false;
    }
}


    /**
     * @return bool
     */
    public static function uninstall(): bool
    {
        try {
            cache()->forget('plugins.balance.installed');
        } catch (\Throwable $e) {
        }

        $paymentMethod = PaymentMethod::where('name', 'balance')->first();
        if (!empty($paymentMethod)) {
            $deleted = $paymentMethod->delete();
            return $deleted > 0;
        }

        return false;
    }
}
