<?php

namespace PHPDojo\Models;

use PHPDojo\Helpers\DatabaseConnection;

/***
 * Represents a User in the Db
 */
class TodoListItem extends Model
{
    public const TABLE_NAME = 'todo_items';

    private const PREFIX = 'todo_item';
    public const COLUMN_ID = self::PREFIX . '_id';
    public const COLUMN_Title = self::PREFIX . '_title';
    public const COLUMN_TODO_LIST_ID = self::PREFIX . '_todo_list_id';

    private const NAME_ID = 'id';
    private const NAME_TITLE = 'title';
    private const NAME_TODO_LIST_ID = 'todo_list_id';

    public function __construct() {
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public final static function all() {
        $sql = 'SELECT * ' .
            ' FROM ' . self::getTableName() . ';';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return array();
        }
        $todoListItems = array();
        while ($row = $result->fetch_assoc()) {  //binded to variables!
            $todoItem = new TodoListItem();
            $todoItem->setId($row[self::COLUMN_ID]);
            $todoItem->setTitle($row[self::COLUMN_Title]);
            $todoItem->setTodoListId($row[self::COLUMN_TODO_LIST_ID]);
            $todoListItems[] = $todoItem;
        }
        return $todoListItems;
    }

    public final static function allForList(TodoList $todoList) {
        $sql = 'SELECT * ' .
            ' FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_TODO_LIST_ID . '=?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $todoListId = $todoList->getId();
        $stmt->bind_param('i', $todoListId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return array();
        }
        $todoListItems = array();
        while ($row = $result->fetch_assoc()) {  //binded to variables!
            $todoItem = new TodoListItem();
            $todoItem->setId($row[self::COLUMN_ID]);
            $todoItem->setTitle($row[self::COLUMN_Title]);
            $todoItem->setTodoListId($row[self::COLUMN_TODO_LIST_ID]);
            $todoListItems[] = $todoItem;
        }
        return $todoListItems;
    }

    public final static function find(int $id) {
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_ID . '=?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return null;
        }
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $todoItem = new TodoListItem();
            $todoItem->setId($row[self::COLUMN_ID]);
            $todoItem->setTitle($row[self::COLUMN_Title]);
            $todoItem->setTodoListId($row[self::COLUMN_TODO_LIST_ID]);
            return $todoItem;
        }
        throw new \Exception('There should be only max one ' . substr(static::getTableName(), 0, -1));
    }

    public static function delete(int $id): void {
        if(self::find($id) == null) {
            throw new \Exception("Could not delete item, because there is no item in db for id: " . $id);
        }
        $sql = 'DELETE FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_ID . '=?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    /**
     * @param mixed $id
     */
    private function setId($id): void {
        $this->setValueForKey(self::NAME_ID, $id);
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->getValueForKey(self::NAME_ID);
    }

    /**
     * @param mixed $name
     */
    public function setTitle(string $title): void {
        $this->setValueForKey(self::NAME_TITLE, trim($title));
    }

    /**
     * @return mixed
     */
    public function getTitle(): string {
        return $this->getValueForKey(self::NAME_TITLE);
    }

    public function setTodoListId(int $todoListId): void {
        $this->setValueForKey(self::NAME_TODO_LIST_ID, $todoListId);
    }

    /**
     * @return mixed
     */
    public function getTodoListId(): int {
        return $this->getValueForKey(self::NAME_TODO_LIST_ID);
    }

    public function save() {
        if (!$this->isSet(self::NAME_TITLE)) {
            throw new \Exception('Title is not set!');
        }
        if (!$this->isSet(self::NAME_TODO_LIST_ID)) {
            throw new \Exception('TodoList id is not set!');
        }
        $title = $this->getValueForKey(self::NAME_TITLE);
        //id is set -> update
        if ($this->isSet(self::NAME_ID)) {
            $id = $this->getValueForKey(self::COLUMN_ID);
            $sql = 'UPDATE ' . self::getTableName() . ' SET ' . self::COLUMN_Title . '=? WHERE ' . self::COLUMN_ID . '=?;';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $stmt->bind_param('si', $title, $id);
            $stmt->execute();
        }
        //no id -> insert new one
        else {
            $sql = 'INSERT INTO ' . self::getTableName() . ' (' . self::COLUMN_Title . ', ' . self::COLUMN_TODO_LIST_ID . ') VALUES (?, ?) ' .
                'ON DUPLICATE KEY UPDATE ' . self::COLUMN_Title . '=?;';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $todoListId = $this->getValueForKey(self::NAME_TODO_LIST_ID);

            $stmt->bind_param('sss', $title, $todoListId,
                $title);
            $stmt->execute();
            $id = $stmt->insert_id;
            if ($id < 0) {
                throw new \Exception("Something went wrong during insert!");
            }
            $this->setId($id);
        }
    }

    public static function create($data): void {
        // TODO: Implement create() method.
    }


    protected static function getCreateSql(): string {
        return 'CREATE TABLE IF NOT EXISTS ' . self::getTableName() . '(' .
            self::COLUMN_ID . ' int(11) NOT NULL AUTO_INCREMENT, ' .
            self::COLUMN_Title . ' varchar(30) NOT NULL, ' .
            self::COLUMN_TODO_LIST_ID . ' int(11) NOT NULL, ' .
            ' PRIMARY KEY (' . self::COLUMN_ID . '), ' .
            ' KEY foreignkey_todolist (' . self::COLUMN_TODO_LIST_ID . '), ' .
            ' CONSTRAINT foreignkey_todolist FOREIGN KEY (' . self::COLUMN_TODO_LIST_ID . ') ' .
            'REFERENCES ' . TodoList::getTableName() . '(' . TodoList::COLUMN_ID . ') ON DELETE CASCADE ON UPDATE CASCADE ' .
            ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ';
    }
}