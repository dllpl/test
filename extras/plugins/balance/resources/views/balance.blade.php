<div id="messageBlock" class="alert alert-danger" style="display: none;"></div>

<div class="row payment-plugin" id="balancePayment" style="display: none;">
    <div class="col-md-10 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">
            <div class="col-xl-12 text-center">
                <img class="img-fluid" src="{{ url('images/balance/payment.png') }}" title="{{ trans('balance::messages.Payment with Balance') }}" alt="">
                <p></p>
            </div>
        </div>
    </div>
</div>
@section('after_scripts')
    @parent
    <script>
        $(document).ready(function () {
            var selectedPackage = $('input[name=package_id]:checked').val();
            var packagePrice = getPackagePrice(selectedPackage);
            var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');

            /* Check Payment Method */
            checkPaymentMethodForPaymentMethod(paymentMethod, packagePrice);
            
            $('#paymentMethodId').on('change', function () {
                paymentMethod = $(this).find('option:selected').data('name');
                checkPaymentMethodForPaymentMethod(paymentMethod, packagePrice);
            });

            $('.package-selection').on('click', function () {
                selectedPackage = $(this).val();
                packagePrice = getPackagePrice(selectedPackage);
                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
                checkPaymentMethodForPaymentMethod(paymentMethod, packagePrice);
            });

            /* Send Payment Request */
            $('#submitPostForm').on('click', function (e) {
                e.preventDefault();

                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');

                if (paymentMethod == 'balance' && packagePrice > 0) {
                    var userId = {{ auth()->user()->id }}; // Получаем ID пользователя из Laravel
                    checkBalanceAndSubmit(userId, packagePrice);
                } else {
                    $('#postForm').submit();
                }

                return false;
            });
        });

        function checkPaymentMethodForPaymentMethod(paymentMethod, packagePrice) {
            if (paymentMethod == 'balance' && packagePrice > 0) {
                $('#balancePayment').show();
            } else {
                $('#balancePayment').hide();
            }
        }

        function checkBalanceAndSubmit(userId, packagePrice) {
            $.ajax({
                url: `https://automost.pro/api/balance/${userId}`,
                type: 'GET',
                headers: {
                    'X-AppApiToken': 'SURMYU5jNEMyZm5CMjJkWmVjcTZzMXFlMFozYTRrWXc='
                },
                success: function (response) {
                    if (response.balance >= packagePrice) {
                        // Если баланс достаточен, отправляем форму
                        $('#postForm').submit();
                    } else {
                        // Выводим сообщение об недостатке средств
                        // Выводим сообщение об недостатке средств
                        $('#messageBlock').text('Недостаточно средств, пополните баланс или смените способ оплаты.').show();
                    }
                },
                error: function () {
                    // Выводим сообщение об недостатке средств
                    $('#messageBlock').text('Произошла ошибка при проверке баланса.').show();
                }
            });
        }
    </script>
@endsection
