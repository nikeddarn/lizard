<?php

return [
    'phone' => [
        // alpha sms config
        'alphasms' => [
            'sent_xml_message_url' => 'https://alphasms.ua/api/xml.php',
            'wsdl' => 'https://alphasms.ua/api/soap.php?wsdl',
            'login' => 'nikeddarn',
            'password' => 'assodance2010',
            'sender' => 'AlphaSMS',
            'key' => '4092f1157b86c199840da099fc1608e504e5776b',
        ],

        // cache ttl of sms sender's balance (minutes)
        'sms_sender_balance_ttl' => 60,

        // notify if balance less than (UAH)
        'low_balance_limit' => 20,
    ],
];
