<?php
namespace App\Libraries;

use Com\Wowza\Application;
use Com\Wowza\Entities\Application\Helpers\Settings;
use Com\Wowza\Server;
use Com\Wowza\StreamFile;

class Wowza_lib
{
    // private $framework;
    private $server;
    private $application;
    private $streamFile;

    public function __construct()
    {
        define("WOWZA_HOST", "http://192.168.20.251:8087/v2");
        define("WOWZA_SERVER_INSTANCE", "_defaultServer_");
        define("WOWZA_VHOST_INSTANCE", "_defaultVHost_");
        define("WOWZA_USERNAME", "hapity");
        define("WOWZA_PASSWORD", "admin123");

// It is simple to create a setup object for transporting our settings
        $setup = new Settings();
        $setup->setHost(WOWZA_HOST);
        $setup->setUsername(WOWZA_USERNAME);
        $setup->setPassword(WOWZA_PASSWORD);

// Connect to the server or deal with statistics NOTICE THE CAPS IN COM AND WOWZA
        $this->server = new Server($setup);
        $this->application = new Application($setup);

    }

    public function get_server_stats()
    {
        // $this->streamFile = new StreamFile();
        // $response = $this->streamFile->create($urlProps);
        $response = $this->application->getAll();
        dd($response);
    }

    public function get_list_of_applications()
    {

    }

}
