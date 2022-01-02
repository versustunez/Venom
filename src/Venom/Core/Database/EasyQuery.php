<?php

namespace Venom\Core\Database;

// the QueryBuilder is stupid! dont use it for Very Complex Queries because it's should do Entity Loading Easier :)
class EasyQuery
{
    const ORDER_ASC = 0;
    const ORDER_DESC = 1;
    const WHERE_AND = "AND";
    const WHERE_AND_NOT = "AND NOT";
    const WHERE_OR = "OR";
    const WHERE_OR_NOT = "OR NOT";
    const WHERE_NOT = "NOT";

    private array $where = [];
    private array $args = [];
    private string $query = "";
    private int $limit = -1;
    private int $offset = 0;
    private string $whereStmt = "";
    private string $havingStmt = "";
    private array $order = [];
    private array $groupBy = [];
    private array $having = [];

    public function __construct(private string $tableName, private array $fields = [])
    {
    }

    public static function createSelect(array $fields, string $table): string
    {
        return "SELECT " . implode(", ", $fields) . " FROM " . $table;
    }

    public function setWhere(string $statement): static
    {
        $this->whereStmt = $statement;
        return $this;
    }

    public function setHaving(string $statement): static
    {
        $this->havingStmt = $statement;
        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function addField($field, $as = ""): static
    {
        if ($as !== "") {
            $field .= " AS " . $as;
        }
        $this->fields[] = $field;
        return $this;
    }

    public function addFields(array $fields): static
    {
        foreach ($fields as $field) {
            $this->fields[] = $field;
        }
        return $this;
    }

    public function where($key, $value, $type = "AND"): static
    {
        $this->where[] = [$key, $type];
        $this->args[":" . $key] = $value;
        return $this;
    }

    public function having($key, $value, $type = "AND"): static
    {
        $this->having[] = [$key, $type];
        $this->args[":" . $key] = $value;
        return $this;
    }

    public function orderBy(string $key, int $mode = self::ORDER_ASC): static
    {
        $this->order[] = $mode === self::ORDER_DESC ? $key . " DESC" : $key;
        return $this;
    }

    public function groupBy(string $key): static
    {
        $this->groupBy[] = $key;
        return $this;
    }

    public function setArg($key, $value): static
    {
        $this->args[":" . $key] = $value;
        return $this;
    }

    // returns a Query

    public function addArgAndField($key, $value): static
    {
        $this->args[":" . $key] = $value;
        $this->fields[] = $key;
        return $this;
    }

    public function buildSelect(): static
    {
        // we build an easyQuery Builder that can very easy stuff
        $query = self::createSelect($this->fields, $this->tableName);
        if (count($this->where) > 0) {
            $this->whereStmt = $this->parseStmt($this->where, $this->whereStmt);
        }
        if (count($this->having) > 0) {
            $this->havingStmt = $this->parseStmt($this->having, $this->havingStmt);
        }
        if ($this->whereStmt !== "") {
            $query .= " WHERE " . $this->whereStmt;
        }
        if (count($this->groupBy)) {
            $query .= " GROUP BY " . implode(", ", $this->groupBy);
        }
        if ($this->havingStmt !== "") {
            $query .= " HAVING " . $this->havingStmt;
        }
        if (count($this->order)) {
            $query .= " ORDER BY " . implode(", ", $this->order);
        }
        if ($this->offset > 0) {
            $query .= " OFFSET " . $this->offset;
        }
        if ($this->limit > 0) {
            $query .= " LIMIT " . $this->limit;
        }
        $this->query = $query;
        return $this;
    }

    public function buildInsertQuery(): static
    {
        $query = "INSERT INTO " . $this->tableName;
        $joinedFields = implode(", ", $this->fields);
        $values = implode(", ", array_keys($this->args));
        $query .= "(" . $joinedFields . ") VALUES (" . $values . ")";
        $this->query = $query;
        return $this;
    }

    public function buildUpdateQuery(): static
    {
        $query = "UPDATE " . $this->tableName . " SET ";
        $setFields = [];
        foreach ($this->fields as $field) {
            $setFields[] = $field . " = :" . $field;
        }
        $query .= implode(", ", $setFields);
        if (count($this->where) > 0) {
            $this->whereStmt = $this->parseStmt($this->where, $this->whereStmt);
        }
        if ($this->whereStmt !== "") {
            $query .= " WHERE " . $this->whereStmt;
        }
        $this->query = $query;
        return $this;
    }

    public function buildDeleteQuery(): static
    {
        $query = "DELETE FROM " . $this->tableName;
        if (count($this->where) > 0) {
            $this->whereStmt = $this->parseStmt($this->where, $this->whereStmt);
        }
        if ($this->whereStmt !== "") {
            $query .= " WHERE " . $this->whereStmt;
        }
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    private function parseStmt($items, $default = ""): string
    {
        $query = $default;
        foreach ($items as $item) {
            if ($query !== "") {
                $query .= " " . $item[1] . " ";
            }
            if ($item[1] === self::WHERE_NOT && $query === "") {
                $query .= "NOT ";
            }
            $query .= $item[0] . " = :" . $item[0];
        }
        return $query;
    }
}
