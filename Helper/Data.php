<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */
namespace Groomershop\FullBreadcrumbs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const ENABLED = 'full_breadcrumbs/general/enabled';

    public $config;

    /**
     * Data constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->config = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * @param string $configPath
     * @return bool
     */
    public function hasConfig($configPath)
    {
        return $this->config->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isEnabled()
    {
        return $this->hasConfig(self::ENABLED);
    }
}
