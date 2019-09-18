<?php
/**
 * Infobeans_WhatsApp Module
 * @category    Infobeans
 * @package     Infobeans_WhatsApp
 * @version     1.0.0
 * @description Module Regstration Class
 * @author      Infobeans
 * @codingStandardsIgnoreStart
 * @codeCoverageIgnoreStart
 */
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
