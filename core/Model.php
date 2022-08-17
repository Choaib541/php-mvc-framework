<?php

namespace app\core;

abstract class Model
{

    public int $id;
    public string $created_at;
    public string|null $updated_at;
    public static Database $query;
    public static string $table = "";

    public function load(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    protected static function db(): Database
    {
        return Application::$app->database;
    }

    public function refresh_data(): void
    {
        $data = self::db()->select(static::$table)->where("id", $this->id)->first();
        $this->load($data);
    }

    public static function find(int $id): bool|static
    {
        $result = self::db()->select(static::$table)->where("id", $id)->first();
        if (!empty($result)) {
            $instance = new static();
            $instance->load($result);
            return $instance;
        } else {
            return false;
        }
    }

    public static function all(): array
    {
        $users = self::db()->select(static::$table)->get();

        return array_map(function ($user) {
            $instance = new static();
            $instance->load($user);
            return $instance;
        }, $users);

    }

    public function update(array $columns_value)
    {

        if (isset($columns_value["created_at"])) {
            unset($columns_value["created_at"]);
        }

        if (isset($columns_value["updated_at"])) {
            unset($columns_value["updated_at"]);
        }

        if (isset($columns_value["id"])) {
            unset($columns_value["id"]);
        }

        self::db()->update(static::$table, $columns_value)->where("id", $this->id)->exec();
        $this->refresh_data();
        return $this;
    }

    public function save(): void
    {
        $this->update(get_object_vars($this));
    }

    public static function create(array $columns_value): bool|static
    {
        self::db()->insert(static::$table, $columns_value);

        $last_id = self::db()->last_inserted_id();

        return self::find($last_id["id"]);

    }

    public function delete(): void
    {
        self::db()->destroy(static::$table)->where("id", $this->id)->exec();
    }

    public static function delete_(int $id): static
    {
        self::$query = self::db()->destroy(static::$table);
        return new static();
    }

    public static function empty(): void
    {
        self::db()->destroy(static::$table);
    }

    public static function latest(): static
    {
        self::$query = self::db()->select(static::$table);
        return new static();
    }

    public function where(string $column1, string $column2, string $operator = "="): static
    {
        self::$query = self::$query->where($column1, $column2, $operator);
        return $this;
    }

    public function get(): array
    {
        $result = self::$query->get();

        if (!empty($result)) {
            $body = [];
            foreach ($result as $instance_data) {
                $instance = new static();
                $instance->load($instance_data);
                $body[] = $instance;
            }
            return $body;
        }
        return $result;
    }

    public static function update_(array $columns_value = []): static
    {
        self::$query = self::db()->update(static::$table, $columns_value);
        return new static();
    }

    public function exec(): bool
    {
        self::$query->exec();
        return TRUE;
    }

}