<?php

namespace Helper;

use Yandex\Allure\Adapter\Support\AttachmentSupport;

class Shot extends \Codeception\Module
{
    use AttachmentSupport;

    public function _failed(\Codeception\TestInterface $test, $fail)
    {
        $testName = $test->getMetadata()->getName();
        $env = $test->getMetadata()->getCurrent('env');
        if($env != null){
            $envName = str_replace('-', '.', $env) . '.';
            codecept_debug($envName);
            $shot = codecept_output_dir() . $testName . 'Cept.' . $envName . 'fail.png';
        } else{
            $shot = codecept_output_dir() . $testName . 'Cept.fail.png';
        }

        codecept_debug($shot);
        if(!file_exists($shot)){
            print("###### Screenshot file does not exist! ######");
        }
        $this->addAttachment($shot,'Screenshot', 'image/png');
    }
}