<?php

namespace Log1x\Pomf;

use Carbon\Carbon;
use Bulletproof\Image;
use Hashids\Hashids;
use Exception;

class Pomf
{
    /**
     * Default Configuration
     *
     * @var array
     */
    protected $config = [
        'token'     => '',
        'dir'       => 'screenshots/',
        'size'      => 100,
        'slug'      => 'Screenshot_',
        'timestamp' => 'Y-m-d_H-i-s'
    ];

    /**
     * Create a new Pomf instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (! $_SERVER['REQUEST_METHOD'] == 'POST') {
            return;
        }

        $config = file_exists($config = __DIR__ . '/../config.php') ? require_once($config) : [];
        $this->config = (object) array_merge($this->config, $config);

        $this->verifyToken();
        $this->upload();
    }

    /**
     * Responds with a JSON body that the request was invalid.
     *
     * @param  array $value
     * @param  int   $code
     * @return mixed
     */
    protected function error($value, $code = 500)
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code((int) $code);

        echo json_encode([
            'status'      => $code,
            'success'     => false,
            'errorcode'   => $code,
            'description' => $value
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Responds with a JSON body that the request was successful.
     *
     * @param  string $value
     * @return mixed
     */
    protected function success($value)
    {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(200);

        echo json_encode([
            'status'  => $code,
            'success' => true,
            'files'   => $value
        ], JSON_PRETTY_PRINT);
    }

    /**
     * Returns the current host URL.
     *
     * @return string
     */
    protected function getHost()
    {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
    }

    /**
     * Generates the filename based on the format.
     *
     * @return string
     */
    protected function generateFilename()
    {
        if (empty($this->config->timestamp)) {
            return $this->config->slug . (new Hashids(random_bytes(24)))->encode(1, 2, 3);
        }

        return $this->config->slug . Carbon::now()->format($this->config->timestamp);
    }

    /**
     * Returns the full relative path to the uploaded file with extension.
     *
     * @param  object $image
     * @return string
     */
    protected function getFile($image = null)
    {
        if (! $image instanceof Image) {
            return $this->error('Unable to process uploaded file.');
        }

        return $this->getHost() . $this->config->dir . $image->getName() . '.' . $image->getMime();
    }

    /**
     * Verifies API Token
     *
     * @return mixed
     */
    protected function verifyToken()
    {
        if (empty($_GET['token']) || $_GET['token'] !== $this->config->token) {
            return $this->error('Invalid Token', 401);
        }
    }

    /**
     * Passes multiple images through Bulletproof and bundles them into an array.
     *
     * @param  array $images
     * @return array
     */
    protected function bundle($images)
    {
        return array_map(function ($image) {
            $image = new Image($image);

            $image->setName($this->generateFilename())
                  ->setSize(100, $this->config->size * (1024 * 1024))
                  ->setMime(['gif', 'jpg', 'jpeg', 'png'])
                  ->setLocation($this->config->dir ? $this->config->dir : '.', 0755);

            return $image;
        }, $images);
    }

    /**
     * Processes and uploads an image passed through the POST method.
     *
     * @param  array $images
     * @return void
     */
    protected function upload($images = [])
    {
        if (empty($_FILES)) {
            $this->error('No input file(s) found.');
        }

        foreach ($this->bundle($_FILES) as $image) {
            if ($image->upload()) {
                $images[] = [
                    'name' => $image->getName(),
                    'url'  => $this->getFile($image),
                    'hash' => sha1_file($image->getFullPath()),
                    'size' => $image->getSize(),
                ];
            }
        }

        if (empty($images)) {
            return $this->error('Images uploaded are not valid.');
        }

        return $this->success($images);
    }
}

if (class_exists('Log1x\\Pomf\\Pomf') || ! file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    return;
}

require_once $composer;

return new Pomf;
