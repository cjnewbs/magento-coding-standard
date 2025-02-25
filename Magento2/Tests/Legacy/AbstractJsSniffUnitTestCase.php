<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento2\Tests\Legacy;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Implements an abstract base for unit tests that cover less sniffs.
 */
abstract class AbstractJsSniffUnitTestCase extends AbstractSniffUnitTest
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $config = new Config();
        $config->extensions = array_merge(
            $config->extensions,
            [
                'js' => 'PHP'
            ]
        );
        
        $GLOBALS['PHP_CODESNIFFER_CONFIG'] = $config;
    }
}
