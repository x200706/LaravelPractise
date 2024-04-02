<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Support\Facades\Mail;

class SMTPMaillTest extends TestCase
{
  // ./vendor/phpunit/phpunit/phpunit tests/Feature/SMTPMaillTest.php --group Feature/SMTPMaillTest
    /**
     * @group Feature/SMTPMaillTest
     */
    public function testSMTPMail()
    {
      Mail::fake();
      // assertSent
    }
}
