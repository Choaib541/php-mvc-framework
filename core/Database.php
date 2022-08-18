<?php

namespace app\core;

use PDO;

class Database
{

    public PDO $pdo;
    protected string $query;

    public function __construct(array $config)
    {

        $dsn = sprintf("%s:host=%s;port=%s;dbname=%s", $config["connection"], $config["host"], $config["port"], $config["database"]);
        $username = $config["username"];
        $password = $config["password"];
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }


    public function migrate()
    {

        $this->create_migrations_table();
        $migrations = scandir(Application::$ROOT_DIR . "/migrations");
        $already_migrated = $this->already_migrated();
        $to_be_migrated = array_diff($migrations, $already_migrated);
        $recently_migrated = [];

        if (count($to_be_migrated) > 2) {

            foreach ($to_be_migrated as $migration) {

                if ($migration === "." || $migration === "..") {
                    continue;
                }

                require_once Application::$ROOT_DIR . "/migrations/$migration";

                $classname = basename($migration, ".php");

                $instance = new $classname();

                $migrating_result = $instance->up();

                if ($migrating_result[0]) {


                    // "\e[0;31;42m$$$$\e[0m\n";
                    $this->log(sprintf("%s - \e[0;32;40mFinished migrating\e[0m \n", $classname));
                    $this->store_migrations($migration);
                } else {
                    $this->log(sprintf("%s - \e[1;31;40mFailed the migrating process \e[0m \n\e[1;33;40mReason: \e[0m $migrating_result[1] \n", $classname));
                }

            }

        } else {

            $this->log("Nothing to migrate");

        }
    }


    public function store_migrations(string $migration): void
    {
        $statement = $this->pdo->prepare("INSERT INTO migrations(migration) VALUES(:migration)");
        $statement->bindValue(":migration", $migration);
        $statement->execute();
    }

    public function log(string $message)
    {
        echo sprintf("[%s] - %s", date("Y-m-d h:i:s"), $message);
    }

    public function create_migrations_table(): void
    {
        $this->pdo->exec("
        
            CREATE TABLE IF NOT EXISTS migrations(
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ); 
        
        ");
    }

    public function already_migrated(): array
    {

        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);

    }


    public function select(string $table, array $columns = ["*"]): static
    {
        $columns = implode(",", $columns);

        $query = "SELECT $columns FROM $table ";

        $this->query = $query;

        return $this;
    }

    public function destroy(string $table): static
    {
        $this->query = "DELETE FROM $table ";
        return $this;
    }


    public function get_fetch_columns(): array
    {
        try {
            $statement = $this->pdo->prepare($this->query);
            $statement->execute();
            $this->query = "";
            return $statement->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\Exception $err) {
            Application::$app->handle_error(message: $err->getMessage(), sql: $this->query);
        }
    }

    public function update(string $table, array $columns_values): static
    {

        $query = "UPDATE $table SET ";
        $column_value = [];

        foreach ($columns_values as $column => $value) {
            $column_value[] = "$column = '$value'";
        }

        $imploded_column_value = implode(",", $column_value);

        $query .= $imploded_column_value . " ";

        $this->query = $query;

        return $this;

    }

    public function insert(string $table, array $columns_values): static
    {

        $columns = implode(",", array_keys($columns_values));

        $values = array_map(function ($value) {
            return "'$value'";
        }, array_values($columns_values));

        $imploded_values = implode(",", $values);

        $query = "INSERT INTO $table" . "($columns)" . " VALUES($imploded_values) ";

        $this->query = $query;


        return $this;

    }

    public function exec(): bool
    {
        try {
            $this->pdo->exec($this->query);
            return TRUE;
        } catch (\Exception $err) {
            Application::$app->handle_error(message: $err->getMessage(), sql: $this->query);
        }


    }

    public function last_inserted_id(): array
    {

        $statement = $this->pdo->prepare("SELECT LAST_INSERT_ID() as id");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);

    }

    public function where(string $column1, string $column2, string $operator = "="): static
    {

        $this->query .= "WHERE $column1 $operator " . "'" . "$column2" . "'" . " ";

        return $this;

    }

    public function get(): array
    {
        try {
            $statement = $this->pdo->prepare($this->query);
            $statement->execute();
            $this->query = "";
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $err) {
            Application::$app->handle_error(message: $err->getMessage(), sql: $this->query);
        }
    }

    public function join(string $table, string $column1, string $column2, string $operator = "="): static
    {

        $this->query .= "JOIN $table ON $column1 $operator $column2 ";
        return $this;

    }

    public function order_by(array $columns, bool $desc = FALSE): static
    {

        $columns = implode(",", $columns);
        $this->query .= "ORDER BY $columns " . ($desc ? "DESC " : " ");
        return $this;

    }

    public function group_by(string $column): static
    {

        $this->query .= "GROUP BY $column ";
        return $this;

    }


    public function having(string $column1, string $column2, string $operation = "="): static
    {

        $this->query .= "HAVING $column1 $operation $column2 ";
        return $this;

    }

    public function first(): array
    {
        $result = $this->get();
        return $result[0] ?? [];
    }

}
