<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);

namespace Magento2\Tests\PHP;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

class AutogeneratedClassNotInConstructorUnitTest extends AbstractSniffUnitTest
{
    /**
     * @inheritdoc
     */
    public function getErrorList($filename = '')
    {
        if ($filename === 'AutogeneratedClassNotInConstructorUnitTest.1.php.inc') {
            return [
                26 => 1,
            ];
        }
        if ($filename === 'AutogeneratedClassNotInConstructorUnitTest.2.php.inc') {
            return [
                14 => 1,
            ];
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getWarningList($filename = '')
    {
        return [];
    }
}
