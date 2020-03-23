<?php

namespace PHPDojo\Models;

use http\Exception;
use PHPDojo\Helpers\DatabaseConnection;

/***
 * Represents a TodoList in the Db
 */
class TodoList extends Model
{
    public const TABLE_NAME = 'todo_lists';

    private const PREFIX = 'todo_list';
    public const COLUMN_ID = self::PREFIX . '_id';
    public const COLUMN_NAME = self::PREFIX . '_name';
    public const COLUMN_USER_ID = self::PREFIX . '_user_id';

    private const NAME_ID = 'id';
    private const NAME_USER = 'user';
    private const NAME_NAME = 'name';
    private const NAME_TODO_ITEMS = 'todo_items';

    private $itemsToRemove = [];

    public function __construct() {
        $this->setValueForKey(self::NAME_TODO_ITEMS, []);
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public final static function all() {
        $sql = 'SELECT ' . self::COLUMN_ID . ', ' . self::COLUMN_NAME . ', ' . self::COLUMN_USER_ID .
            ' FROM ' . self::getTableName();
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return array();
        }
        $todoLists = array();
        while ($row = $result->fetch_assoc()) {
            $todoList = new TodoList();
            $todoList->setValueForKey(self::NAME_ID, $row[self::COLUMN_ID]);
            $todoList->setValueForKey(self::NAME_NAME, $row[self::COLUMN_NAME]);
            $todoList->setValueForKey(self::NAME_USER, User::find($row[self::COLUMN_USER_ID]));
            $todoList->setValueForKey(self::NAME_TODO_ITEMS, TodoListItem::allForList($todoList));
            $todoLists[] = $todoList;
        }
        return $todoLists;
    }

    public final static function allForUser($user) {
        $sql = 'SELECT ' . self::COLUMN_ID . ', ' . self::COLUMN_NAME . ', ' . self::COLUMN_USER_ID .
            ' FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_USER_ID . '=?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $userId = $user->getId();
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return array();
        }
        $todoLists = array();
        while ($row = $result->fetch_assoc()) {
            $todoList = new TodoList();
            $todoList->setValueForKey(self::NAME_ID, $row[self::COLUMN_ID]);
            $todoList->setValueForKey(self::NAME_NAME, $row[self::COLUMN_NAME]);
            $todoList->setValueForKey(self::NAME_USER, User::find($row[self::COLUMN_USER_ID]));
            $todoList->setValueForKey(self::NAME_TODO_ITEMS, TodoListItem::allForList($todoList));
            $todoLists[] = $todoList;
        }
        return $todoLists;
    }

    public final static function find($id) {
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_ID . '=?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new \Exception("There exists no list for given id: " . $id);
        }
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $todoList = new TodoList();
            $todoList->setValueForKey(self::NAME_ID, $row[self::COLUMN_ID]);
            $todoList->setValueForKey(self::NAME_NAME, $row[self::COLUMN_NAME]);
            $todoList->setValueForKey(self::NAME_USER, User::find($row[self::COLUMN_USER_ID]));
            $todoList->setValueForKey(self::NAME_TODO_ITEMS, TodoListItem::allForList($todoList));
            return $todoList;
        }
        throw new \Exception('There should be only max one ' . substr(static::getTableName(), 0, -1));
    }

    /**
     * @param mixed $id
     */
    private function setId($id): void {
        $this->setValueForKey(self::NAME_ID, $id);

        //set the id for all items
        $items = $this->getItems();
        foreach ($items as $item) {
            $item->setTodoListId($id);
        }
    }

    /**
     * @return mixed
     */
    public function getId(): int {
        return $this->getValueForKey(self::NAME_ID);
    }

    /**
     * returns the user for this list
     * if there is no user, null is returned
     * (could not use nullable types due to php server version 7.3)
     * @return User
     */
    public function getUser()  {
        if($this->isSet(self::NAME_USER)) {
            return $this->getValueForKey(self::NAME_USER);
        }
        return null;
    }

    /**
     * Setter for the userid,
     * @param User $user the user for this list
     * @throws \Exception if user == null
     */
    public function setUser(User $user) : void {
        if($user == null) {
            throw new \Exception('User = null is not allowed!');
        }
        $this->setValueForKey(self::NAME_USER, $user);
    }

    /** Getter for list name
     * @return string the name of this list
     */
    public function getName(): string {
        return $this->getValueForKey(self::NAME_NAME);
    }

    /** Setter for the name
     * The min length for names is 1
     * @param mixed $name the name for this list
     */
    public function setName(string $name): void {
        if($name == null || strlen($name) < 1) {
            throw new \Exception("No valid name! Null or empty!");
        }
        $this->setValueForKey(self::NAME_NAME, trim($name));
    }

    /** Getter for list items
     * @return array of list items
     */
    public function getItems(): array {
        return $this->getValueForKey(self::NAME_TODO_ITEMS);
    }

    public function addItem(TodoListItem $toAdd) {
        if($toAdd == null) {
            throw new \Exception("Item to add is null!");
        }
        $items = $this->getItems();
        $alreadyExists = false;
        foreach ($items as $item) {
            if(($this->hasId() && $item->getId() == $toAdd->getId()) || $item->getTitle() == $toAdd->getTitle()) {
                $alreadyExists = true;
                break;
            }
        }
        if($alreadyExists) {
            throw new \Exception("There exists already an item with this id/name");
        }
        else {
            $this->addValueForKeyArray(self::NAME_TODO_ITEMS, $toAdd);
        }
    }

    public function removeItem(TodoListItem $toRemove) {
        if($toRemove == null) {
            throw new \Exception("Item to remove is null!");
        }
        $itemsWith = $this->getItems();
        $itemsWithout = array();
        foreach ($itemsWith as $item) {
            if($item->getId() != $toRemove->getId()) {
                $itemsWithout[] = $item;
            }
            else {
                $this->itemsToRemove[] = $item;
            }
        }
        $this->setValueForKey(self::NAME_TODO_ITEMS, $itemsWithout);
    }

    public function save() {
        if (!$this->isSet(self::NAME_NAME) || $this->getName() == '') {
            throw new \Exception('Name is not set or empty!');
        }
        if (!$this->isSet(self::NAME_USER)) {
            throw new \Exception('User is not set!');
        }
        $name = $this->getName();
        $userId = $this->getUser()->getId();
        //id is set -> do update
        if ($this->isSet(self::NAME_ID)) {
            $id = $this->getId();
            $sql = 'UPDATE '. self::getTableName() . ' SET ' . self::COLUMN_NAME . '=? WHERE ' . self::COLUMN_ID . '=?;';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $stmt->bind_param('si', $name, $id);
            $stmt->execute();
        }
        //no id -> do insert
        else {
            $sql = "INSERT INTO " . self::getTableName() .
                ' (' . self::COLUMN_NAME . ', ' . self::COLUMN_USER_ID . ')' .
                ' VALUES (?, ?) ON DUPLICATE KEY UPDATE ' .
                self::COLUMN_NAME . '=?;';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $stmt->bind_param("sis", $name, $userId, $name);
            $stmt->execute();
            $newId = $stmt->insert_id;
            if($newId < 0) {
                throw new \Exception("Something went wrong during insert!");
            }
            $this->setId($newId);
        }

        //remove the toRemove from db
        foreach ($this->itemsToRemove as $toRemove) {
            TodoListItem::delete($toRemove->getid());
        }
        $this->toRemove = [];

        //2) add all items
        $items = $this->getItems();
        foreach ($items as $item) {
            $item->setTodoListId($id);
            $item->save();
        }
    }

    /**
     * Deletes the list for the given id
     * Items are deleted automatically (cascade delete)
     * @param $data an associative array, containing the deletetion id, key: todoListDeleteId
     * @throws \Exception throws an Exception if there is no id
     */
    public static function delete($data): void {
        if ($data != null && isset($data['todoListDeleteId'])) {
            $id = $data['todoListDeleteId'];
            $sql = 'DELETE FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_ID . '=?;';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
        }
        else {
            throw new \Exception("Could not delete: No todo-list id given!");
        }
    }

    /**
     * The create sql statement for this table
     *
     * Foreign-Key:
     *  - delete-cascade on deletion: items should be removed if there is no corresponding list!
     * @return string
     */
    protected static function getCreateSql(): string {
        return 'CREATE TABLE IF NOT EXISTS ' . self::getTableName() . '(' .
            self::COLUMN_ID . ' int(11) NOT NULL AUTO_INCREMENT, ' .
            self::COLUMN_NAME . ' varchar(30) NOT NULL, ' .
            self::COLUMN_USER_ID . ' int(11) NOT NULL, ' .
            ' PRIMARY KEY (' . self::COLUMN_ID . '), ' .
            ' KEY foreignkey_user (' . self::COLUMN_USER_ID . '), ' .
            ' CONSTRAINT `foreignkey_user` FOREIGN KEY (' . self::COLUMN_USER_ID . ') ' .
            'REFERENCES ' . User::getTableName() . '(' . User::COLUMN_ID . ') ON DELETE CASCADE ON UPDATE CASCADE ' .
            ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ';
    }


}