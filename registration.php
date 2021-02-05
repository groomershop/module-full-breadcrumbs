<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Groomershop_FullBreadcrumbs',
    isset($file) && realpath($file) == __FILE__ ? dirname($file) : __DIR__
);
