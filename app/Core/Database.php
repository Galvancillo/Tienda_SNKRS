<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $connection;
    private $config;
    private static $instance = null;
    private $inTransaction = false;

    public function __construct() {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->connect();
    }

    /**
     * Implementación del patrón Singleton para asegurar una única conexión
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        try {
            // Verificar que la configuración existe
            if (!isset($this->config['database'])) {
                throw new PDOException("La configuración de la base de datos no está definida");
            }

            $dbConfig = $this->config['database'];

            // Verificar que todos los parámetros necesarios existen
            $requiredParams = ['host', 'dbname', 'user', 'charset'];
            foreach ($requiredParams as $param) {
                if (!isset($dbConfig[$param])) {
                    throw new PDOException("Parámetro de configuración faltante: {$param}");
                }
            }

            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$dbConfig['charset']}"
            ];

            $this->connection = new PDO(
                $dsn,
                $dbConfig['user'],
                $dbConfig['password'] ?? '',
                $options
            );

            // Verificar la conexión ejecutando una consulta simple
            $this->connection->query("SELECT 1");

        } catch (PDOException $e) {
            // Log del error
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            
            // Mensaje de error más amigable para el usuario
            throw new PDOException(
                "No se pudo conectar a la base de datos. Por favor, verifica la configuración."
            );
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Log del error real
            error_log("Error en la consulta SQL: " . $e->getMessage());
            error_log("Consulta: " . $sql);
            error_log("Parámetros: " . print_r($params, true));
            
            // Mensaje de error más amigable
            throw new PDOException(
                "Ocurrió un error al ejecutar la consulta. Por favor, inténtalo de nuevo."
            );
        }
    }

    public function prepare($sql) {
        try {
            return $this->connection->prepare($sql);
        } catch (PDOException $e) {
            error_log("Error al preparar la consulta: " . $e->getMessage());
            throw new PDOException(
                "Ocurrió un error al preparar la consulta. Por favor, inténtalo de nuevo."
            );
        }
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction() {
        if (!$this->inTransaction) {
            $this->connection->beginTransaction();
            $this->inTransaction = true;
        }
    }

    public function commit() {
        if ($this->inTransaction) {
            $this->connection->commit();
            $this->inTransaction = false;
        }
    }

    public function rollBack() {
        if ($this->inTransaction) {
            $this->connection->rollBack();
            $this->inTransaction = false;
        }
    }

    public function isInTransaction() {
        return $this->inTransaction;
    }
} 