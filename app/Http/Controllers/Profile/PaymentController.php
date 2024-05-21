<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Quiz\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use YooKassa\Client;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Payment\PaymentStatus;

class PaymentController extends Controller {

    /**
     * Display the user's profile form.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Profile/Payment/Index', [
            'items' => Payment::where('user_id', '=', \Auth::id())
                ->whereIn('status', [ Payment::STATUS_DONE, Payment::STATUS_NEW])
                ->get()
                ->append('description')
        ]);
    }

    public function create(Request $request){

        list($items, $amount) = $this->getValidatedOrder($request);

        if(!empty(\Auth::id())){
            return Inertia::render('Profile/Payment/Create', [
                'items' => $items,
                'amount' => $amount
            ]);
        } else {
            return redirect()->setIntendedUrl($request->getRequestUri())->to('/login');
        }
    }

    public function store(Request $request) {

        list($items, $amount) = $this->getValidatedOrder($request);

        $payment = Payment::create([
            'amount' => $amount,
            'items' => $items
        ]);

        $client = new Client();
        $client->setAuth(env('YOOKASSA_SHOPID'), env('YOOKASSA_SECRET'));

        $yooPayment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => number_format($amount, 2, '.', ''),
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => route('profile-payment.status', ['payment' => $payment], true),
                ),
                'capture' => true,
                'description' => 'Заказ №' . $payment->id,
            ),
            uniqid('', true)
        );

        if ($yooPayment->status == PaymentStatus::PENDING) {
            $payment->update(['external_id' => $yooPayment->getId()]);
            return [
                'result' => 'ok',
                'redirect_to' => $yooPayment->getConfirmation()->getConfirmationUrl()
            ];
        } else {
            throw new \Exception('Invalid responce from yoomoney');
        }

    }


    protected function getValidatedOrder(Request $request){
        $blocks = Block::active()->find($request->ids);

        $items = [];
        $amount = 0;
        /** @var Block $block */
        foreach ($blocks as $block) {
            $items[] = [
                'id' => $block->id,
                'title' => $block->title,
                'amount' => $block->price
            ];
            $amount += $block->price;
        }
        return [$items, $amount];
    }


    public function show(Payment $payment) {
        $confirmationUrl = null;
        if($payment->status == 'new' && !empty($payment->external_id)){
            $client = new Client();
            $client->setAuth(env('YOOKASSA_SHOPID'), env('YOOKASSA_SECRET'));
            $yooPayment = $client->getPaymentInfo($payment->external_id);
            if ($yooPayment->status == PaymentStatus::PENDING) {
                $confirmationUrl = $yooPayment->getConfirmation()->getConfirmationUrl();
            }
        }
        return Inertia::render('Profile/Payment/Show', [
            'payment' => $payment,
            'show_status' => \Session::get('status', false),
            'confirmation_url' => $confirmationUrl
        ]);
    }

    public function status(Payment $payment){

        $this->checkPaymentStatus($payment);

        return redirect()->route('profile-payment.show', ['payment' => $payment])->with('status', true);
    }


    public function yookassaWebHook() {

        try {
            $source = file_get_contents('php://input');

            $data = json_decode($source, true);

            $factory = new \YooKassa\Model\Notification\NotificationFactory();
            $notificationObject = $factory->factory($data);
            $responseObject = $notificationObject->getObject();

            $client = new \YooKassa\Client();

            if (!$client->isNotificationIPTrusted($_SERVER['REMOTE_ADDR'])) {
                header('HTTP/1.1 400 Something went wrong');
                exit();
            }

            $event = $notificationObject->getEvent();
            if (in_array($event, [
                NotificationEventType::PAYMENT_SUCCEEDED,
                NotificationEventType::PAYMENT_CANCELED
            ])) {
                $id = $responseObject->getId();
                $payment = Payment::where('external_id', '=', $id)->orderBy('id', 'DESC')->first();
                if (!empty($payment) && $payment->status == Payment::STATUS_NEW) {
                    if ($event == NotificationEventType::PAYMENT_SUCCEEDED) {
                        $payment->done();
                        Log::info('Payment ' . $payment->id . ' is Done');
                    } else if ($event == NotificationEventType::PAYMENT_CANCELED) {
                        $payment->cancel();
                        Log::info('Payment ' . $payment->id . ' is Canceled');
                    }
                }
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 400 Something went wrong 2');
            exit();
        }

        return response('OK', 200);
    }

    public function checkPaymentStatus(Payment $payment): void {
        if ($payment->status == 'new' && !empty($payment->external_id)) {
            $client = new Client();
            $client->setAuth(env('YOOKASSA_SHOPID'), env('YOOKASSA_SECRET'));
            $yooPayment = $client->getPaymentInfo($payment->external_id);
            if ($yooPayment->status == PaymentStatus::SUCCEEDED) {
                $payment->done();
                Log::info('Payment ' . $payment->id . ' is Done');
            } else if ($yooPayment->status == PaymentStatus::CANCELED) {
                $payment->cancel();
                Log::info('Payment ' . $payment->id . ' is Canceled');
            }
        }
    }


}
