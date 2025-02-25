<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);

namespace Magento2\Sniffs\Legacy;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Tests to find usage of restricted code
 */
class RestrictedCodeSniff implements Sniff
{
    private const ERROR_MESSAGE = "Class '%s' is restricted in %s. Suggested replacement: %s";
    private const ERROR_CODE = "restrictedClass";

    /**
     * List of fixtures that contain restricted classes and should not be tested
     *
     * @var array
     */
    private $fixtureFiles = [];

    /**
     * Restricted classes
     *
     * @var array
     */
    private $classes = [];

    /**
     * RestrictedCodeSniff constructor.
     */
    public function __construct()
    {
        $this->loadData('restricted_classes*.php');
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_STRING,
            T_CONSTANT_ENCAPSED_STRING
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // phpcs:ignore
        if (array_key_exists(basename($phpcsFile->getFilename()), $this->fixtureFiles)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr]['content'];
        if (array_key_exists($token, $this->classes)) {
            if ($this->isExcluded($token, $phpcsFile)) {
                return;
            }
            $phpcsFile->addError(
                sprintf(
                    self::ERROR_MESSAGE,
                    $token,
                    $phpcsFile->getFilename(),
                    $this->classes[$token]['replacement']
                ),
                $stackPtr,
                self::ERROR_CODE,
            );
        }
    }

    /**
     * Checks if currently parsed file should be excluded from analysis
     *
     * @param string $token
     * @param File $phpcsFile
     * @return bool
     */
    private function isExcluded(string $token, File $phpcsFile): bool
    {
        if (in_array($phpcsFile->getFilename(), $this->fixtureFiles)) {
            return true;
        }
        foreach ($this->classes[$token]['exclude'] as $exclude) {
            if (strpos($phpcsFile->getFilename(), $exclude) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Loads and merges data from fixtures
     *
     * @param string $filePattern
     * @return void
     */
    private function loadData(string $filePattern)
    {
        // phpcs:ignore
        foreach (glob(__DIR__ . '/_files/' . $filePattern) as $file) {
            $relativePath = str_replace(
                '\\',
                '/',
                str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file)
            );
            array_push($this->fixtureFiles, $relativePath);
            $this->classes = array_merge_recursive($this->classes, $this->readList($file));
        }
    }

    /**
     * Isolate including a file into a method to reduce scope
     *
     * @param string $file
     * @return array
     */
    private function readList($file)
    {
        // phpcs:ignore
        return include $file;
    }
}
