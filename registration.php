<?php


\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Infobeans_WhatsApp',
    __DIR__
);

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::LIBRARY,
    'Twilio_Sdk',
    BP . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR .
    'twilio' . DIRECTORY_SEPARATOR . 'sdk' . DIRECTORY_SEPARATOR . 'Twilio'
);
