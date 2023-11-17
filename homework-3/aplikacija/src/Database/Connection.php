<?php

namespace App\Database;

class Connection
{
    private const DSN = 'pgsql:host=localhost;user=postgres;password=pass;dbname=akademija';
    private static self $instance;
    private \PDO $connection;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function startTransaction(): void
    {
        $this->connection()->exec('START TRANSACTION');
    }

    public function commit(): void
    {
        $this->connection()->exec('COMMIT');
    }

    public function rollback(): void
    {
        $this->connection()->exec('ROLLBACK');
    }
    public function insert(string $table, array $params): int
    {
        $fields = array_keys($params);

        $sql = sprintf(
            'INSERT INTO %s(%s) VALUES(%s)',
            $table,
            implode(', ', $fields),
            implode(', ', array_map(static fn (string $field) => ':'.$field, $fields)),
        );

        $statement = $this->connection()->prepare($sql);

        $statement->execute($params);

        return $this->connection()->lastInsertId();
    }

    public function update(string $table, array $params, int $id): int
    {
        $sql = sprintf(
            'UPDATE %s SET %s WHERE id = :id',
            $table,
            implode(', ', array_map(static fn (string $field) => "$field = :$field", array_keys($params))),
        );

        $statement = $this->connection()->prepare($sql);

        $statement->execute($params + ['id' => $id]);

        return $statement->rowCount();
    }

    public function find(string $table, array $fields = [], array $where = []): array
    {
        return $this->select($table, $fields, $where)->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function findOne(string $table, array $fields = [], array $where = []): ?array
    {
        return $this->select($table, $fields, $where, 1)->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    private function select(string $table, array $fields = [], array $where = [], ?int $limit = null): \PDOStatement
    {
        $sql = sprintf(
            'SELECT %s FROM %s',
            $fields ? implode(', ', $fields) : '*',
            $table,
        );

        if ($where) {
            $sql .= sprintf(
                ' WHERE %s',
                implode(' AND ', array_map(static fn (string $field) => "$field = :$field", array_keys($where))),
            );
        }

        if ($limit) {
            $sql .= sprintf(' LIMIT %d', $limit);
        }

        $statement = $this->connection()->prepare($sql);

        $statement->execute($where);

        return $statement;
    }

    private function connection(): \PDO
    {
        return $this->connection ??= new \PDO(self::DSN);
    }
}