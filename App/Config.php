<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'mvc';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Secret key for hashing
     * @var boolean
     */
    const SECRET_KEY = 't39QuFtRV7AjJvJZvXGwUsnuaSmF7lMv';
	
    /**
     * Mailgun API key
     *
     * @var string
     */
    const MAILGUN_API_KEY = '09fac21dfaf5e181d42a1941c1054d7e-2fbe671d-a13a7a66';

    /**
     * Mailgun domain
     *
     * @var string
     */
    const MAILGUN_DOMAIN = 'sandboxc44aa722cf214823aafa6642065f8502.mailgun.org';
}
