<?php
/**
 * RSA Crypt
 *
 * Copyright (c) 2015, Dmitry Mamontov <d.slonyara@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Dmitry Mamontov nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   rsacrypt
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @since     File available since Release 1.0.2
 */


 /**
 * RsaCryptTest - test class
 *
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version   Release: 1.0.2
 * @link      https://github.com/dmamontov/asynctask
 * @since     Class available since Release 1.0.2
 */
 
class RsaCryptTest extends PHPUnit_Framework_TestCase
{
    public function testGeneratedKeys()
    {
        $crypt = null;
        try {
            $crypt = new RsaCrypt();
        } catch (\RuntimeException $e) {
        }

        if (is_null($crypt) == false) {
            $sucess = null;
            try {
                $sucess = $crypt->genKeys(512);
            } catch (\RuntimeException $e) {
            }
            $this->assertNotNull($sucess);

            $sucess = null;
            try {
                $sucess = $crypt->genKeys(1024);
            } catch (\RuntimeException $e) {
            }
            $this->assertNotNull($sucess);

            $sucess = null;
            try {
                $sucess = $crypt->genKeys(999);
            } catch (\RuntimeException $e) {
            }
            $this->assertNull($sucess);

            $sucess = null;
            try {
                $sucess = $crypt->genKeys();
            } catch (\RuntimeException $e) {
            }

            $this->assertNotNull($sucess);
            $this->assertTrue($sucess);

            if ($sucess === true) {
                $this->assertFileExists(__DIR__ . '/../src/private.pem');
                if (file_exists(__DIR__ . '/../src/private.pem')) {
                    $file = file_get_contents(__DIR__ . '/../src/private.pem');
                    $this->assertNotEmpty($file);
                    $this->assertNotFalse(stripos($file, 'PRIVATE KEY'));
                    $this->assertNotFalse(stripos($file, 'PRIVATE KEY'));
                }

                $this->assertFileExists(__DIR__ . '/../src/public.pem');
                if (file_exists(__DIR__ . '/../src/public.pem')) {
                    $file = file_get_contents(__DIR__ . '/../src/public.pem');
                    $this->assertNotEmpty($file);
                    $this->assertNotFalse(stripos($file, 'PUBLIC KEY'));
                    $this->assertNotFalse(stripos($file, 'PUBLIC KEY'));
                }
            }
        }

        @unlink(__DIR__ . '/../src/private.pem');
        @unlink(__DIR__ . '/../src/public.pem');
    }

    public function testSetAndGetKeys()
    {
        $crypt = null;
        try {
            $crypt = new RsaCrypt();
        } catch (\RuntimeException $e) {
        }

        if (is_null($crypt) == false) {
            $sucess = null;
            try {
                $sucess = $crypt->setPublicKey(__DIR__ . '/.files/public.pem');
            } catch (\RuntimeException $e) {
            }
            $this->assertTrue($sucess);

            $this->assertNotFalse($crypt->getPublicKey());
            $this->assertEquals($crypt->getPublicKey(), __DIR__ . '/.files/public.pem');

            $sucess = null;
            try {
                $sucess = $crypt->setPrivateKey(__DIR__ . '/.files/private.pem');
            } catch (\RuntimeException $e) {
            }
            $this->assertTrue($sucess);

            $this->assertNotFalse($crypt->getPrivateKey());
            $this->assertEquals($crypt->getPrivateKey(), __DIR__ . '/.files/private.pem');
        }
    }

    public function testEncryptAndDecrypt()
    {
        $crypt = null;
        try {
            $crypt = new RsaCrypt();
        } catch (\RuntimeException $e) {
        }

        if (is_null($crypt) == false) {
            try {
                $crypt->setPublicKey(__DIR__ . '/.files/public.pem');
            } catch (\RuntimeException $e) {
            }

            try {
                $crypt->setPrivateKey(__DIR__ . '/.files/private.pem');
            } catch (\RuntimeException $e) {
            }

            $encrypt = null;
            try {
                $encrypt = $crypt->encrypt('Test encrypt');
            } catch (\RuntimeException $e) {
            }
            $this->assertNotNull($encrypt);

            $decrypt = null;
            try {
                $decrypt = $crypt->decrypt($encrypt);
            } catch (\RuntimeException $e) {
            }
            $this->assertNotNull($encrypt);

            if (is_null($encrypt) === false && is_null($decrypt) === false) {
                $this->assertEquals('Test encrypt', $decrypt);
            }
        }
    }
}
