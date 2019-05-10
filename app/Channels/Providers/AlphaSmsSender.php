<?php

/*
 * Sending an sms via https://alphasms.ua/api/soap.php?wsdl .
 */

namespace App\Channels\Providers;

use App\Contracts\Channels\SmsChannelSenderInterface;
use App\Contracts\Channels\SmsTypesInterface;
use App\Messages\SmsMessage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SimpleXMLElement;

class AlphaSmsSender implements SmsChannelSenderInterface, SmsTypesInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AlphaSmsSender constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send an sms.
     *
     * @param SmsMessage $message
     * @return bool Description
     * @throws GuzzleException
     */
    public function send(SmsMessage $message)
    {
        $url = config('channels.phone.alphasms.sent_xml_message_url');
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $this->createMessageData($message),
        ];

        $response = $this->client->request('POST', $url, $options);

        $responseContent = $response->getBody()->getContents();

        $responseXml = new SimpleXMLElement($responseContent);
        $status = $responseXml->message[0]->msg[0];

        return $status === '1';
    }

    /**
     * Get sms sender balance.
     *
     * @return float
     * @throws GuzzleException
     */
    public function getBalance(): float
    {
        $url = config('channels.phone.alphasms.sent_xml_message_url');
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $this->createBalanceData(),
        ];

        $response = $this->client->request('POST', $url, $options);

        $responseContent = $response->getBody()->getContents();

        $responseXml = new SimpleXMLElement($responseContent);

        $balance = $responseXml->balance[0]->amount;

        return floatval($balance);
    }

    /**
     * Create message data.
     *
     * @param SmsMessage $message
     * @return mixed
     */
    private function createMessageData(SmsMessage $message)
    {
        $alphasmsKey = config('channels.phone.alphasms.key');

        $sender = $message->sender ? $message->sender : config('channels.phone.alphasms.sender');
        $type = $message->type ? $message->type : self::SIMPLE;

        $xmlPackage = new SimpleXMLElement('<package></package>');
        $xmlPackage->addAttribute('key', $alphasmsKey);

        $xmlMessage = $xmlPackage->addChild('message');

        $xmlMsg = $xmlMessage->addChild('msg', $message->text);

        $xmlMsg->addAttribute('recipient', $message->recipient);
        $xmlMsg->addAttribute('sender', $sender);
        $xmlMsg->addAttribute('type', $type);

        if ($message->beginTransfer) {
            $xmlMsg->addAttribute('date_beg', $message->beginTransfer);
        }

        if ($message->endTransfer) {
            $xmlMsg->addAttribute('date_end', $message->endTransfer);
        }

        if ($xmlMsg->url) {
            $xmlMessage->addAttribute('url', $message->url);
        }

        return $xmlPackage->asXML();
    }

    /**
     * Create message data.
     *
     * @return mixed
     */
    private function createBalanceData()
    {
        $alphasmsKey = config('channels.phone.alphasms.key');

        $xmlPackage = new SimpleXMLElement('<package></package>');
        $xmlPackage->addAttribute('key', $alphasmsKey);

        $xmlPackage->addChild('balance');

        return $xmlPackage->asXML();
    }
}
