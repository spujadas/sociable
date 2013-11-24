<?php

/*
 * This file is part of the Sociable package.
 *
 * Copyright 2013 by Sébastien Pujadas
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace Sociable\Tests\Model;

use Sociable\Model\MultiCurrencyValue,
    Sociable\Model\Currency,
    Sociable\Utility\NumberValidator,
    Sociable\Utility\StringValidator;

class MultiCurrencyValueTest extends \PHPUnit_Framework_TestCase {

    protected $multiCurrencyValue;

    const DEFAULT_CURRENCY_CODE = 'EUR';
    const CURRENCY_VALUE1_VALUE = 15;
    const CURRENCY_VALUE1_CURRENCY_CODE = 'EUR';
    const CURRENCY_VALUE2_VALUE = 10;
    const CURRENCY_VALUE2_CURRENCY_CODE = 'GBP';
    
    const DEFAULT_CURRENCY_CODE_MISMATCH = 'USD';
    
    public function setUp() {
        $this->multiCurrencyValue = new MultiCurrencyValue();
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\MultiCurrencyValue', $this->multiCurrencyValue);
        
        $this->multiCurrencyValue = 
                new MultiCurrencyValue(self::CURRENCY_VALUE1_VALUE, 
                        self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->assertEquals(self::CURRENCY_VALUE1_CURRENCY_CODE, 
                $this->multiCurrencyValue->getDefaultCurrencyCode());
        $this->assertEquals(self::CURRENCY_VALUE1_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(self::CURRENCY_VALUE1_CURRENCY_CODE));
    }

    public function testSetDefaultCurrencyCodeNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->multiCurrencyValue->setDefaultCurrencyCode(null);
    }
    
    public function testGetDefaultCurrencyCodeNotAString() {
        try {
            $this->multiCurrencyValue->setDefaultCurrencyCode(null);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->multiCurrencyValue->getDefaultCurrencyCode());
    }
    
    public function testSetDefaultCurrencyCodeIncorrectLength() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_INCORRECT_LENGTH);
        $this->multiCurrencyValue->setDefaultCurrencyCode(str_repeat('a', Currency::CODE_LENGTH + 1));
    }
    
    public function testGetDefaultCurrencyCodeIncorrectLength() {
        try {
            $this->multiCurrencyValue->setDefaultCurrencyCode(str_repeat('a', Currency::CODE_LENGTH + 1));
        }
        catch (\Exception $e) {}
        $this->assertNull($this->multiCurrencyValue->getDefaultCurrencyCode());
    }

    public function testSetGetDefaultCurrencyCode() {
        $this->assertEquals(self::DEFAULT_CURRENCY_CODE, 
                $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE));
        $this->assertEquals(self::DEFAULT_CURRENCY_CODE, 
                $this->multiCurrencyValue->getDefaultCurrencyCode());
    }
    
    public function testAddCurrencyValueDuplicateCurrency() {
        $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->setExpectedException('Sociable\Model\MultiCurrencyValueException', 
            MultiCurrencyValue::EXCEPTION_DUPLICATE_CURRENCY);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
    }

    public function testSetGetCurrencyValue() {
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->assertEquals(self::CURRENCY_VALUE1_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(self::CURRENCY_VALUE1_CURRENCY_CODE));
    }
    
    public function testGetCurrencyValueNonExistingCurrencyCode() {
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->assertNull($this->multiCurrencyValue
                ->getValueByCurrencyCode(self::CURRENCY_VALUE2_CURRENCY_CODE));
    }
    
    public function testSetGetValue() {
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE2_CURRENCY_CODE);
        $this->assertEquals(self::CURRENCY_VALUE1_VALUE, 
                $this->multiCurrencyValue
                ->getValueByCurrencyCode(self::CURRENCY_VALUE1_CURRENCY_CODE));
    }
    
    public function testGetValueNonExistingCurrencyCode() {
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->assertNull($this->multiCurrencyValue
                ->getValueByCurrencyCode(self::CURRENCY_VALUE2_CURRENCY_CODE));
    }
    
    public function testValidateUninitialised() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->multiCurrencyValue->validate();
    }

    public function testValidateUnmatchedDefault() {
        $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE_MISMATCH);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->setExpectedException('Sociable\Model\MultiCurrencyValueException', 
            MultiCurrencyValue::EXCEPTION_UNMATCHED_DEFAULT);
        $this->multiCurrencyValue->validate();
    }

    public function testAddCurrencyValue() {
        $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE2_CURRENCY_CODE);
        $this->multiCurrencyValue->validate();
        $this->assertEquals(self::CURRENCY_VALUE1_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE1_CURRENCY_CODE));
        $this->assertEquals(self::CURRENCY_VALUE2_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE2_CURRENCY_CODE));
    }
    
    public function testAddValueByCurrencyCode() {
        $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(
                self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(
                self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE2_CURRENCY_CODE);
        $this->assertEquals(self::CURRENCY_VALUE1_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE1_CURRENCY_CODE));
        $this->assertEquals(self::CURRENCY_VALUE2_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE2_CURRENCY_CODE));
        $this->multiCurrencyValue->addValueByCurrencyCode(
                self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE, true);
        $this->assertEquals(self::CURRENCY_VALUE2_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE1_CURRENCY_CODE));
        $this->setExpectedException('Sociable\Model\MultiCurrencyValueException', 
            MultiCurrencyValue::EXCEPTION_DUPLICATE_CURRENCY);
        $this->multiCurrencyValue->addValueByCurrencyCode(
                self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
    }
    
    public function testRemoveCurrencyValueByCurrencyCode() {
        $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(
                self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(
                self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE2_CURRENCY_CODE);
        $this->multiCurrencyValue->validate();
        $this->assertEquals(2, count($this->multiCurrencyValue->getCurrencyValues()));
        $this->assertEquals(self::CURRENCY_VALUE1_VALUE, 
                $this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE1_CURRENCY_CODE));
        $this->assertTrue($this->multiCurrencyValue->removeValueByCurrencyCode(
                self::CURRENCY_VALUE2_CURRENCY_CODE));
        $this->assertFalse($this->multiCurrencyValue->removeValueByCurrencyCode(
                self::CURRENCY_VALUE2_CURRENCY_CODE));
        $this->assertEquals(1, count($this->multiCurrencyValue->getCurrencyValues()));
        $this->assertNull($this->multiCurrencyValue->getValueByCurrencyCode(
                        self::CURRENCY_VALUE2_CURRENCY_CODE));
    }
    
    public function testSumCurrencyValueInvalidCurrencyValue() {
        $this->multiCurrencyValue->setDefaultCurrencyCode(self::DEFAULT_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE1_VALUE, 
                self::CURRENCY_VALUE1_CURRENCY_CODE);
        $this->multiCurrencyValue->addValueByCurrencyCode(self::CURRENCY_VALUE2_VALUE, 
                self::CURRENCY_VALUE2_CURRENCY_CODE);
        $this->setExpectedException('Exception');
        $this->multiCurrencyValue->sumCurrencyValue('', '');
    }

    public function testSum() {
        $multiCurrencyValue1 = new MultiCurrencyValue;
        $multiCurrencyValue1->setDefaultCurrencyCode('USD');
        $multiCurrencyValue1->addValueByCurrencyCode(0, 'USD');
        $multiCurrencyValue1->addValueByCurrencyCode(10, 'EUR');
        $multiCurrencyValue1->validate();
        $multiCurrencyValue2 = new MultiCurrencyValue;
        $multiCurrencyValue2->setDefaultCurrencyCode('GBP');
        $multiCurrencyValue2->addValueByCurrencyCode(10, 'USD');
        $multiCurrencyValue2->addValueByCurrencyCode(10, 'GBP');
        $multiCurrencyValue2->validate();
        $multiCurrencyValue1->sum($multiCurrencyValue2);
        $multiCurrencyValue1->validate();
        $this->assertEquals('USD', $multiCurrencyValue1->getDefaultCurrencyCode());
        $this->assertEquals(10., $multiCurrencyValue1->getValueByCurrencyCode('USD'));
        $this->assertEquals(10., $multiCurrencyValue1->getValueByCurrencyCode('EUR'));
        $this->assertEquals(10., $multiCurrencyValue1->getValueByCurrencyCode('GBP'));
    }
    
    public function testSumCurrencyValue() {
        $multiCurrencyValue1 = new MultiCurrencyValue;
        $multiCurrencyValue1->setDefaultCurrencyCode('USD');
        $multiCurrencyValue1->addValueByCurrencyCode(0, 'USD');
        $multiCurrencyValue1->addValueByCurrencyCode(10, 'EUR');
        $multiCurrencyValue1->validate();
        $multiCurrencyValue1->sumCurrencyValue(10, 'USD');
        $multiCurrencyValue1->sumCurrencyValue(0, 'EUR');
        $multiCurrencyValue1->sumCurrencyValue(10, 'GBP');
        $multiCurrencyValue1->validate();
        $this->assertEquals(10., $multiCurrencyValue1->getValueByCurrencyCode('USD'));
        $this->assertEquals(10., $multiCurrencyValue1->getValueByCurrencyCode('EUR'));
        $this->assertEquals(10., $multiCurrencyValue1->getValueByCurrencyCode('GBP'));
    }
    
    public function testCompareValues() {
        $multiCurrencyValue = new MultiCurrencyValue;
        $multiCurrencyValue->setDefaultCurrencyCode('USD');
        $multiCurrencyValue->addValueByCurrencyCode(10, 'USD');
        $multiCurrencyValue->addValueByCurrencyCode(10, 'EUR');
        $multiCurrencyValue->validate();
        $multiCurrencyValueCompare = new MultiCurrencyValue;
        $multiCurrencyValueCompare->setDefaultCurrencyCode('GBP');
        $multiCurrencyValueCompare->addValueByCurrencyCode(10, 'USD');
        $multiCurrencyValueCompare->addValueByCurrencyCode(10, 'GBP');
        $multiCurrencyValueCompare->validate();
        $this->assertEquals(MultiCurrencyValue::COMPARE_DIFFERENT_LIST_OF_CURRENCIES,
                $multiCurrencyValue->compareValues($multiCurrencyValueCompare));
        $multiCurrencyValueCompare->addValueByCurrencyCode(10, 'EUR');
        $multiCurrencyValueCompare->validate();
        $this->assertEquals(MultiCurrencyValue::COMPARE_DIFFERENT_NUMBER_OF_CURRENCIES,
                $multiCurrencyValue->compareValues($multiCurrencyValueCompare));
        $multiCurrencyValueCompare->setDefaultCurrencyCode('USD');
        $multiCurrencyValueCompare->removeValueByCurrencyCode('GBP');
        $this->assertEquals(MultiCurrencyValue::COMPARE_EQUALS,
                $multiCurrencyValue->compareValues($multiCurrencyValueCompare));
        $multiCurrencyValueCompare->addValueByCurrencyCode(20, 'USD', true);
        $this->assertEquals(MultiCurrencyValue::COMPARE_DIFFERENT_VALUE,
                $multiCurrencyValue->compareValues($multiCurrencyValueCompare));
        
    }

    public function tearDown() {
        unset($this->multiCurrencyValue);
    }

}


