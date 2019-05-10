<?php
/**
 * Append common data for all admin views.
 */

namespace App\Http\Composers;


use App\Channels\SmsChannel;
use App\Models\Vendor;
use App\Support\ExchangeRates\ExchangeRates;
use Illuminate\View\View;

class CommonAdminDataComposer
{
    /**
     * @var Vendor
     */
    private $vendor;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var SmsChannel
     */
    private $smsChannel;

    /**
     * CommonDataComposer constructor.
     * @param Vendor $vendor
     * @param ExchangeRates $exchangeRates
     * @param SmsChannel $smsChannel
     */
    public function __construct(Vendor $vendor, ExchangeRates $exchangeRates, SmsChannel $smsChannel)
    {
        $this->vendor = $vendor;
        $this->exchangeRates = $exchangeRates;
        $this->smsChannel = $smsChannel;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        // get locale
        $locale = app()->getLocale();

        $vendors = $this->vendor->newQuery()->orderBy("name_$locale")->get();

        $user = auth('web')->user();

        $headerData = $this->getHeaderData();

        $view->with(compact('user', 'vendors', 'headerData'));
    }

    /**
     * Get data for header.
     *
     * @return array
     */
    private function getHeaderData()
    {
        $course = $this->getCourse();

        $smsSenderBalance = $this->getSmsSenderBalance();

        $disksUsage = $this->getDisksUsage();

        return compact('course', 'smsSenderBalance', 'disksUsage');
    }

    /**
     * Get USD course.
     *
     * @return string
     */
    private function getCourse()
    {
        return number_format($this->exchangeRates->getRate(), 2);
    }

    /**
     * Get sms sender balance.
     *
     * @return array
     */
    private function getSmsSenderBalance()
    {
        $balance = $this->smsChannel->getBalance();
        $isBalanceLow = $balance < config('channels.phone.low_balance_limit');

        return [
            'value' => number_format($balance, 2),
            'low_balance' => $isBalanceLow,
        ];
    }

    /**
     * Get disks usages.
     *
     * @return array
     */
    private function getDisksUsage()
    {
        $mainDisk = '/dev/vda2';
        $cloudDisk = '/dev/sda1';

        return [
            'main' => exec("df $mainDisk -h | tail -1 | awk '{print $5}'"),
            'cloud' => exec("df $cloudDisk -h | tail -1 | awk '{print $5}'"),
        ];
    }
}
