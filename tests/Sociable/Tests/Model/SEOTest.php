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

use Sociable\Utility\SEO,
    Sociable\Utility\StringValidator;

class SEOTest extends \PHPUnit_Framework_TestCase {

    public function testGenerateSlugTooLong() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_TOO_LONG);
        SEO::generateSlug(str_repeat('a', SEO::MAX_LENGTH + 1));
    }
    
    public function testGenerateSlugEmpty() {
        $this->setExpectedException('Sociable\Utility\StringException', 
                StringValidator::EXCEPTION_EMPTY);
        SEO::generateSlug('');
    }
    
    public function testGenerateSlug() {
        $this->assertEquals(
                "this-is-an-example-string-nothing-fancy", 
                SEO::generateSlug("This is an example string. Nothing fancy.")
        );

        // Example using French with unwanted characters ('?)
        $this->assertEquals(
                "qu-en-est-il-francais-ca-marche-alors", 
                SEO::generateSlug("Qu'en est-il français? Ça marche alors?")
        );

        // Example using transliteration
        $this->assertEquals(
                "chto-delat-esli-ya-ne-hochu-utf-8", 
                SEO::generateSlug("Что делать, если я не хочу, UTF-8?", 
                        array('transliterate' => true))
        );

        // Example using transliteration on an unsupported language
        $this->assertEquals(
                "מה-אם-אני-לא-רוצה-utf-8-תווים", 
                SEO::generateSlug("מה אם אני לא רוצה UTF-8 תווים?", 
                        array('transliterate' => true))
        );

        // Some other options
        $this->assertEquals(
                "This_is_a_Test_String_What_s_Going_to_Ha", 
                SEO::generateSlug(
                        "This is an Example String. What's Going to Happen to Me?", 
                        array(
                            'delimiter' => '_',
                            'limit' => 40,
                            'lowercase' => false,
                            'replacements' => array(
                            '/\b(an)\b/i' => 'a',
                            '/\b(example)\b/i' => 'Test'
                        )
                    )
                )
                );

        /*

          Что делать, если я не хочу, UTF-8?
          chto-delat-esli-ya-ne-hochu-utf-8

          מה אם אני לא רוצה UTF-8 תווים?
          מה-אם-אני-לא-רוצה-utf-8-תווים

          This is an Example String. What's Going to Happen to Me?
          This_is_a_Test_String_What_s_Going_to_Ha
         */
    }

}


