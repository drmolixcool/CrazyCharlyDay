<?php

namespace App\factory;

use PDO;
use PDOException;
use App\exception\DatabaseConnectionException;

class ConnectionFactory
{
    public static array $config = [];
    public static ?PDO $context = null;

    public static function setConfig(): void
    {
        self::$config = parse_url(getenv('DATABASE_URL'));
    }

    public static function getConnection(): PDO
    {
        if (is_null(self::$context)) {
            try {self::$context = new PDO(
                    self::$config['scheme'] . ':host=' . self::$config['host'] . ':' . self::$config['port'] . ';dbname=' . substr(self::$config['path'],1),
                    self::$config['user'],
                    self::$config['pass']
                );

                self::$context->exec('SET NAMES utf8');

                self::$context->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$context->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                return self::$context;
            } catch (PDOException $e) {
                throw new DatabaseConnectionException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$context;
    }

    public static function closeConnection(): void
    {
        self::$context = null;
    }

}