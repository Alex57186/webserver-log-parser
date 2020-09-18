<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccessLogTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLFTest()
    {

        $content = [
            '/help_page/1 126.318.035.038',
            '/help_page/1 929.398.951.882',
            '/help_page/1 929.398.951.881',
            '/help_page/1 929.398.951.885',
            '/help_page/1 929.398.951.223',
            '/contact 184.123.665.067',
            '/contact 184.123.665.066',
            '/contact 184.123.345.234',
            '/contact 184.123.345.234',
            '/about 184.123.345.654',
            '/about 184.123.345.652',
            '/about 184.123.345.653',
            '/about 184.123.345.657',
            '/about 184.123.345.657',
            '/about 184.123.345.657',
        ];

        $expect = '/about 6 visits /help_page/1 5 visits /contact 4 visits';

        $expect1 = '/help_page/1 5 visits /about 4 visits /contact 3 visits';


        $random_name = 'webserver11234.log';

        Storage::put($random_name, implode("\n",$content));

        $this->artisan('logs:aggregate webserver11234.log')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);

        $this->artisan('logs:aggregate webserver11234.log --database')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);
    }

    public function testCRLFTest()
    {

        $content = [
            '/help_page/1 126.318.035.038',
            '/help_page/1 929.398.951.882',
            '/help_page/1 929.398.951.881',
            '/help_page/1 929.398.951.885',
            '/help_page/1 929.398.951.223',
            '/contact 184.123.665.067',
            '/contact 184.123.665.066',
            '/contact 184.123.345.234',
            '/contact 184.123.345.234',
            '/about 184.123.345.654',
            '/about 184.123.345.652',
            '/about 184.123.345.653',
            '/about 184.123.345.657',
            '/about 184.123.345.657',
            '/about 184.123.345.657',

        ];

        $expect = '/about 6 visits /help_page/1 5 visits /contact 4 visits';

        $expect1 = '/help_page/1 5 visits /about 4 visits /contact 3 visits';


        $random_name = 'webserver11234.log';

        Storage::put($random_name, implode("\r\n",$content));

        $this->artisan('logs:aggregate webserver11234.log')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);

        $this->artisan('logs:aggregate webserver11234.log --database')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);

    }

    public function testWrongFileName()
    {

        $file = 'wrong-filename';

        $this->artisan("logs:aggregate $file")
            ->expectsOutput('file does not exist')
            ->assertExitCode(0);

        $this->artisan("logs:aggregate $file --database")
            ->expectsOutput('file does not exist')
            ->assertExitCode(0);

        Storage::delete($file);

    }

    public function testEmptyFile()
    {

        $random_name = 'webserver11234.log';

        Storage::put($random_name, '');

        $this->artisan('logs:aggregate webserver11234.log')
            ->expectsOutput('file is empty')
            ->assertExitCode(0);

        $this->artisan('logs:aggregate webserver11234.log --database')
            ->expectsOutput('file is empty')
            ->assertExitCode(0);

    }

    public function testEmptyLinesAtTheEnd()
    {

        $content = [
            '/help_page/1 126.318.035.038',
            '/help_page/1 929.398.951.882',
            '/help_page/1 929.398.951.881',
            '/help_page/1 929.398.951.885',
            '/help_page/1 929.398.951.223',
            '/contact 184.123.665.067',
            '/contact 184.123.665.066',
            '/contact 184.123.345.234',
            '/contact 184.123.345.234',
            '/about 184.123.345.654',
            '/about 184.123.345.652',
            '/about 184.123.345.653',
            '/about 184.123.345.657',
            '/about 184.123.345.657',
            '/about 184.123.345.657',
            "\n",
            "\n",
            "\n",
        ];

        $expect = '/about 6 visits /help_page/1 5 visits /contact 4 visits';

        $expect1 = '/help_page/1 5 visits /about 4 visits /contact 3 visits';


        $random_name = 'webserver11234.log';

        Storage::put($random_name, implode("\n", $content));

        $this->artisan('logs:aggregate webserver11234.log')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);

        $this->artisan('logs:aggregate webserver11234.log --database')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);
    }

    public function testRoutesHasSameNumberOfVisitsCheckOrder()
    {

        $content = [
            '/help_page/1 126.318.035.038',
            '/help_page/1 184.123.665.067',
            '/help_page/1 184.123.345.234',
            '/list 123.234.55.43',
            '/list 123.43.14.431',
            '/list 184.123.345.657',
            '/about 184.123.345.654',
            '/about 184.123.345.653',
            '/about 929.398.951.882',
            '/new 929.398.951.82',
            '/new 929.398.951.884',
            '/new 929.398.951.482',
        ];

        $expect = '/help_page/1 3 visits /about 3 visits /list 3 visits /new 3 visits';
        $expect1 = '/help_page/1 3 visits /about 3 visits /list 3 visits /new 3 visits';


        $random_name = 'webserver11234.log';

        Storage::put($random_name, implode("\n", $content));

        $this->artisan('logs:aggregate webserver11234.log')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);

        $this->artisan('logs:aggregate webserver11234.log --database')
            ->expectsOutput($expect)
            ->expectsOutput($expect1)
            ->assertExitCode(0);
    }

}
