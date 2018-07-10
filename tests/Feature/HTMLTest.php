<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HTMLTest extends TestCase
{
    /**
     * Tests the OG Locale
     */
    public function testOGLocale(){
      $this->get('/')
           ->assertSee('<meta property="og:locale" content="en_US" />');
    }

    /**
     * Tests that the OG type is present
     *
     * @return void
     */
    public function testOGType(){
      $this->get('/')
           ->assertSee('<meta property="og:type" content="website" />');
    }

    /**
     * Tests the OG title is present
     */
    public function testOGTitle(){
      $this->get('/')
           ->assertSee('<meta property="og:title" content="Roast - Helping the coffee enthusiast find their next cup of coffee" />');
    }

    /**
     * Tests the OG URL is present
     */
    public function testOGURL(){
      $this->get('/')
           ->assertSee('<meta property="og:url" content="https://roastandbrew.coffee/" />');
    }

    /**
     * Tests the Site name is present
     */
    public function testOGSiteName(){
      $this->get('/')
           ->assertSee('<meta property="og:site_name" content="Roast and Brew" />');
    }

    /**
     * Tests the OG Image is present
     */
    public function testOGImage(){
      $this->get('/')
           ->assertSee('<meta property="og:image" content="https://roastandbrew.coffee/img/og-image.jpg" />');
    }

    /**
     * Tests the OG Image secure URL is present
     */
     public function testOGImageSecureURL(){
       $this->get('/')
            ->assertSee('<meta property="og:image:secure_url" content="https://roastandbrew.coffee/img/og-image.jpg" />');
     }

     /**
      * Tests the OG Image Width is present
      */
    public function testOGImageWidth(){
      $this->get('/')
           ->assertSee('<meta property="og:image:width" content="1200" />');
    }

    /**
     * Tests the OG Image height is present
     */
     public function testOGImageHeight(){
       $this->get('/')
            ->assertSee('<meta property="og:image:height" content="630" />');
     }

     /**
      * Tests that the Twitter card is present
      *
      * @return void
      */
     public function testTwitterCard()
     {
         $this->get('/')
              ->assertSee('<meta name="twitter:card" content="summary_large_image" />');
     }

     /**
      * Tests that the Twitter description is present
      *
      * @return void
      */
     public function testTwitterDescription()
     {
         $this->get('/')
              ->assertSee('<meta name="twitter:description" content="Welcome to Roast and Brew! We help the coffee enthusiast find their next cup of coffee and teach developers how to develop apps!" />');
     }

     /**
      * Tests that the Twitter title is present
      *
      * @return void
      */
     public function testTwitterTitle()
     {
         $this->get('/')
              ->assertSee('<meta name="twitter:title" content="Roast - Helping the coffee enthusiast find their next cup of coffee" />');
     }

     /**
      * Tests that the Twitter site is present
      *
      * @return void
      */
     public function testTwitterSite()
     {
         $this->get('/')
              ->assertSee('<meta name="twitter:site" content="@roast_n_brew" />');
     }

    /**
     * Tests that the Twitter image is present
     *
     * @return void
     */
    public function testTwitterImage()
    {
        $this->get('/')
             ->assertSee('<meta name="twitter:image" content="https://roastandbrew.coffee/img/og-image.jpg" />');
    }

    /**
     * Tests the Website JSON LD
     */
    public function testWebsiteJSONLD(){
       $this->get('/')
            ->assertSee('"@context":"https:\/\/schema.org"')
            ->assertSee('"@type":"WebSite"')
            ->assertSee('"@id":"#website"')
            ->assertSee('"url":"https:\/\/roastandbrew.coffee\/')
            ->assertSee('"name":"Roast And Brew"');
    }

    /**
     * Tests the Organization JSON LD
     */
    public function testOrganizationJSONLD(){
      $this->get('/')
           ->assertSee('"@context":"https:\/\/schema.org"')
           ->assertSee('"@type":"Organization"')
           ->assertSee('"url":"https:\/\/roastandbrew.coffee\/"')
           ->assertSee('"https:\/\/www.facebook.com/roastandbrewcoffee\/"')
           ->assertSee('"https:\/\/twitter.com\/roast_n_brew"')
           ->assertSee('"@id":"https:\/\/roastandbrew.coffee\/#organization"')
           ->assertSee('"name":"Roast And Brew"')
           ->assertSee('"logo":"https:\/\/roastandbrew.coffee\/img\/og-image.jpg"');
    }
}
