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

use Sociable\Model\ContactDetails,
    Sociable\Utility\EmailValidator,
    Sociable\Utility\SkypeValidator,
    Sociable\Model\MultiLanguageString,
    Sociable\Utility\StringValidator;

class ContactDetailsTest extends \PHPUnit_Framework_TestCase {

    protected $contactDetails;
    protected $email;
    protected $phoneNumber;
    protected $fax;
    protected $mobile;
    protected $skype;
    protected $notes;
    protected $notesTooLong;

    const EMAIL = 'foo@bar.com';
    const PHONE = '0123456789';
    const FAX = '0123456789';
    const MOBILE = '0123456789';
    const SKYPE = 'foo.bar';
    
    public function setUp() {
        $this->contactDetails = new ContactDetails();
        
        $this->notes = new MultiLanguageString('secteur', 'fr');
        
        $this->notesTooLong = new MultiLanguageString(str_repeat('a', ContactDetails::NOTES_MAX_LENGTH + 1), 'fr');
    }

    public function test__construct() {
        $this->assertInstanceOf('Sociable\Model\ContactDetails', $this->contactDetails);
    }

    public function testSetEmailNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->contactDetails->setEmail(array());
    }
    
    public function testGetEmailNotAString() {
        try {
            $this->contactDetails->setEmail(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getEmail());
    }
    
    public function testSetEmailInvalidEmail() {
        $this->setExpectedException('Sociable\Utility\EmailException', 
                EmailValidator::EXCEPTION_INVALID_ADDRESS);
        $this->contactDetails->setEmail('');
    }
    
    public function testGetEmailInvalidEmail() {
        try {
            $this->contactDetails->setEmail('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getEmail());
    }
    
    public function testSetGetEmail() {
        $this->assertEquals(null, $this->contactDetails->setEmail(null));
        $this->assertEquals(null, $this->contactDetails->getEmail());
        $this->assertEquals(self::EMAIL, 
                $this->contactDetails->setEmail(self::EMAIL));
        $this->assertEquals(self::EMAIL, $this->contactDetails->getEmail());
    }

    public function testSetPhoneNumberNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->contactDetails->setPhoneNumber(array());
    }
    
    public function testGetPhoneNumberNotAString() {
        try {
            $this->contactDetails->setPhoneNumber(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getPhoneNumber());
    }
    
    public function testSetPhoneNumberInvalidPhoneNumber() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_SHORT);
        $this->contactDetails->setPhoneNumber('');
    }
    
    public function testGetPhoneNumberInvalidPhoneNumber() {
        try {
            $this->contactDetails->setPhoneNumber('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getPhoneNumber());
    }
    
    public function testSetGetPhoneNumber() {
        $this->assertEquals(null, $this->contactDetails->setPhoneNumber(null));
        $this->assertEquals(null, $this->contactDetails->getPhoneNumber());
        $this->assertEquals(self::PHONE, 
                $this->contactDetails->setPhoneNumber(self::PHONE));
        $this->assertEquals(self::PHONE, $this->contactDetails->getPhoneNumber());
    }

    public function testSetMobileNumberNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->contactDetails->setMobileNumber(array());
    }
    
    public function testGetMobileNumberNotAString() {
        try {
            $this->contactDetails->setMobileNumber(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getMobileNumber());
    }
    
    public function testSetMobileNumberInvalidPhoneNumber() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_SHORT);
        $this->contactDetails->setMobileNumber('');
    }
    
    public function testGetMobileNumberInvalidPhoneNumber() {
        try {
            $this->contactDetails->setMobileNumber('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getMobileNumber());
    }
    
    public function testSetGetMobileNumber() {
        $this->assertEquals(null, $this->contactDetails->setMobileNumber(null));
        $this->assertEquals(null, $this->contactDetails->getMobileNumber());
        $this->assertEquals(self::MOBILE, 
                $this->contactDetails->setMobileNumber(self::MOBILE));
        $this->assertEquals(self::MOBILE, $this->contactDetails->getMobileNumber());
    }

    public function testSetFaxNumberNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->contactDetails->setFaxNumber(array());
    }
    
    public function testGetFaxNumberNotAString() {
        try {
            $this->contactDetails->setFaxNumber(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getFaxNumber());
    }
    
    public function testSetFaxNumberInvalidPhoneNumber() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_SHORT);
        $this->contactDetails->setFaxNumber('');
    }
    
    public function testGetFaxNumberInvalidPhoneNumber() {
        try {
            $this->contactDetails->setFaxNumber('');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getFaxNumber());
    }
    
    public function testSetGetFaxNumber() {
        $this->assertEquals(null, $this->contactDetails->setFaxNumber(null));
        $this->assertEquals(null, $this->contactDetails->getFaxNumber());
        $this->assertEquals(self::FAX, 
                $this->contactDetails->setFaxNumber(self::FAX));
        $this->assertEquals(self::FAX, $this->contactDetails->getFaxNumber());
    }
    
    public function testSetSkypeNameNotAString() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_NOT_A_STRING);
        $this->contactDetails->setSkypeName(array());
    }
    
    public function testGetSkypeNameNotAString() {
        try {
            $this->contactDetails->setSkypeName(array());
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getSkypeName());
    }
    
    public function testSetSkypeNameInvalidSkypeName() {
        $this->setExpectedException('Sociable\Utility\SkypeException', 
                SkypeValidator::EXCEPTION_INVALID_SKYPE_NAME);
        $this->contactDetails->setSkypeName('$$$$$');
    }
    
    public function testGetSkypeNameInvalidSkypeName() {
        try {
            $this->contactDetails->setSkypeName('$$$$$');
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getSkypeName());
    }
    
    public function testSetGetSkypeName() {
        $this->assertEquals(null, $this->contactDetails->setSkypeName(null));
        $this->assertEquals(null, $this->contactDetails->getSkypeName());
        $this->assertEquals(self::SKYPE, 
                $this->contactDetails->setSkypeName(self::SKYPE));
        $this->assertEquals(self::SKYPE, $this->contactDetails->getSkypeName());
    }
    
    public function testSetNotesTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        $this->contactDetails->setNotes($this->notesTooLong);
    }
    
    public function testGetNotesTooLong() {
        try {
            $this->contactDetails->setNotes($this->notesTooLong);
        }
        catch (\Exception $e) {}
        $this->assertNull($this->contactDetails->getNotes());
    }
    
    public function testSetGetNotes() {
        $this->assertEquals($this->notes, 
                $this->contactDetails->setNotes($this->notes));
        $this->assertEquals($this->notes, $this->contactDetails->getNotes());
    }
    
    public function testValidate() {
        $this->contactDetails->validate();
        $this->contactDetails->setEmail(self::EMAIL);
        $this->contactDetails->setPhoneNumber(self::FAX);
        $this->contactDetails->setFaxNumber(self::FAX);
        $this->contactDetails->setMobileNumber(self::MOBILE);
        $this->contactDetails->setSkypeName(self::SKYPE);
        $this->contactDetails->setNotes($this->notes);
        $this->contactDetails->validate();
    }

    public function tearDown() {
        unset($this->contactDetails);
    }

}


