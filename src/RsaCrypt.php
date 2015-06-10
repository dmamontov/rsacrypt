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
 * RsaCrypt - The main class
 *
 * @author    Dmitry Mamontov <d.slonyara@gmail.com>
 * @copyright 2015 Dmitry Mamontov <d.slonyara@gmail.com>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version   Release: 1.0.2
 * @link      https://github.com/dmamontov/rsacrypt/
 * @since     Class available since Release 1.0.2
 */
class RsaCrypt
{
    /**
     * Path to the keys.
     * @var string
     * @access protected
     */
    protected $private, $public;

    /**
     * Checks for the required functions for encryption.
     * @return void
     * @access public
     * @final
     */
    final public function __construct()
    {
        if (
            function_exists('openssl_get_publickey') === false ||
            function_exists('openssl_public_encrypt') === false ||
            function_exists('openssl_get_privatekey') === false ||
            function_exists('openssl_private_decrypt') === false
        ) {
            throw new RuntimeException('Not all the functions of openssl.');
        }
    }

    /**
     * It generates private and public keys with the specified size.
     * @param integer $size
     * @return boolean
     * @access public
     * @final
     */
    final public function genKeys($size = 2048)
    {
        if (function_exists('exec') == false) {
            throw new RuntimeException('Exec function not used.');
        }
        if (in_array($size, array(512, 1024, 2048)) === false) {
            throw new RuntimeException('The key size can only be 512 bits, 1024 bits or 2048 bits. 2048 bits is recommended.');
        }

        @exec(
            "openssl genrsa -out " .
            __DIR__ . "/private.pem $size 2>&1 && openssl rsa -in " .
            __DIR__ . "/private.pem -out " .
            __DIR__ . "/public.pem -outform PEM -pubout 2>&1",
            $out,
            $status
        );

        if ($status == -1) {
            throw new RuntimeException('Error generating keys. Check the settings for openssl.');
        }

        $this->public = 'public.pem';
        $this->private = 'private.pem';

        return true;
    }

    /**
     * Initializes public key.
     * @param integer $key
     * @return boolean
     * @access public
     * @final
     */
    final public function setPublicKey($key)
    {
        if (is_null($key) || empty($key) || file_exists($key) === false) {
            throw new RuntimeException('Wrong key.');
        }

        $this->public = $key;

        return true;
    }

    /**
     * Gets public key.
     * @return boolean
     * @access public
     * @final
     */
    final public function getPublicKey()
    {
        return is_null($this->public) ? false : $this->public;
    }

    /**
     * Initializes private key.
     * @param integer $key
     * @return mixed
     * @access public
     * @final
     */
    final public function setPrivateKey($key)
    {
        if (is_null($key) || empty($key) || file_exists($key) === false) {
            throw new RuntimeException('Wrong key.');
        }

        $this->private = $key;

        return true;
    }

    /**
     * Gets private key.
     * @return mixed
     * @access public
     * @final
     */
    final public function getPrivateKey()
    {
        return is_null($this->private) ? false : $this->private;
    }

    /**
     * Data encryption.
     * @param string $data
     * @return string|boolean
     * @access public
     * @final
     */
    final public function encrypt($data)
    {
        if (is_null($data) || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } elseif (is_null($this->public) || empty($this->public)) {
            throw new RuntimeException('You need to set the public key.');
        }

        $key = @file_get_contents($this->public);
        if ($key) {
            $key = openssl_get_publickey($key);
            openssl_public_encrypt($data, $encrypted, $key);

            return chunk_split(base64_encode($encrypted));
        }

        return false;
    }

    /**
     * Decrypt data.
     * @param string $data
     * @return string|boolean
     * @access public
     * @final
     */
    final public function decrypt($data)
    {
        if (is_null($data) || empty($data) || is_string($data) === false) {
            throw new RuntimeException('Needless to encrypt.');
        } elseif (is_null($this->private) || empty($this->private)) {
            throw new RuntimeException('You need to set the private key.');
        }

        $key = @file_get_contents($this->private);
        if ($key) {
            $key = openssl_get_privatekey($key);
            openssl_private_decrypt(base64_decode($data), $result, $key);

            return reset($result);
        }
    }
}
