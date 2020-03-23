<?php

namespace PHPDojo\Models;

use PHPDojo\Helpers\DatabaseConnection;

/***
 * Represents a User in the Db
 */
class User extends Model
{
    public const TABLE_NAME = 'users';
    private const PREFIX = 'user';
    public const COLUMN_ID = self::PREFIX . '_id';
    public const COLUMN_NAME = self::PREFIX . '_name';
    public const COLUMN_PASSWORD = self::PREFIX . '_password';

    private const NAME_PASSWORD = 'password';
    private const NAME_NAME = 'name';
    private const NAME_ID = 'id';

    public function __construct() {
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public final static function all() {
        $sql = 'SELECT * FROM ' . self::getTableName();
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return array();
        }
        $users = array();
        while ($row = $result->fetch_assoc()) {  //binded to variables!
            $user = new User();
            $user->setId($row[self::COLUMN_ID]);
            $user->setName($row[self::COLUMN_NAME]);
            $user->setPassword($row[self::COLUMN_PASSWORD]);
            $users[] = $user;
        }
        return $users;
    }

    public final static function find($id) {
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
            $user = new User();
            $user->setId($row[self::COLUMN_ID]);
            $user->setName($row[self::COLUMN_NAME]);
            $user->setPassword($row[self::COLUMN_PASSWORD]);
            return $user;
        }
        throw new \Exception('There should be only max one ' . substr(static::getTableName(), 0, -1));
    }

    public static function findByNamePasswd($username, $password) {
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE ' . self::COLUMN_NAME . ' =?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashDb = $row[self::COLUMN_PASSWORD];
            $isVerified = password_verify($password, $hashDb);
            if ($isVerified) {
                $user = new User();
                $user->setId($row[self::COLUMN_ID]);
                $user->setName($row[self::COLUMN_NAME]);
                $user->setPassword($row[self::COLUMN_PASSWORD]);
                return $user;
            }
        }
        return null;
    }

    public
    static function delete(int $id): void {
        $sql = 'DELETE FROM users WHERE id=?;';
        $stmt = DatabaseConnection::mysqli()->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    /**
     * @param mixed $id
     */
    private
    function setId($id): void {
        $this->setValueForKey(self::NAME_ID, $id);
    }

    /**
     * @return mixed
     */
    public
    function getId(): int {
        return $this->getValueForKey(self::NAME_ID);
    }

    /**
     * @return mixed
     */
    public
    function getName(): string {
        return $this->getValueForKey(self::NAME_NAME);
    }

    /**
     * @param mixed $name
     */
    public
    function setName(string $name): void {
        $this->setValueForKey(self::NAME_NAME, trim($name));
    }

    /**
     * @param string $password the hashed password
     */
    public
    function setPassword(string $password): void {
        if (strlen($password) == 60) {
            //we have already a hashed password....
            $this->setValueForKey(self::NAME_PASSWORD, $password);
        }
        else {
            $this->setValueForKey(self::NAME_PASSWORD, self::hashPassword($password));
        }
    }

    public
    function save() {
        if (!$this->isSet(self::NAME_PASSWORD)) {
            throw new \Exception('Password is not set!');
        }
        if (!$this->isSet(self::NAME_NAME)) {
            throw new \Exception('Name is not set!');
        }
        if ($this->isSet(self::NAME_ID)) {
            $sql = 'UPDATE ' . self::getTableName() . ' SET ' . self::COLUMN_NAME . '=?, ' . self::COLUMN_PASSWORD . '=? WHERE ' . self::COLUMN_ID . '=?;';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $stmt->bind_param('ssi', $this->getValueForKey(self::NAME_NAME), $this->getValueForKey(self::NAME_PASSWORD), $this->getValueForKey(self::NAME_ID));
            $stmt->execute();
        }
        else {
            $sql = 'INSERT INTO ' . self::getTableName() . ' (' . self::COLUMN_NAME . ',' . self::COLUMN_PASSWORD . ') VALUES (?, ?) ' .
                ' ON DUPLICATE KEY UPDATE ' . self::COLUMN_NAME . '=?, ' . self::COLUMN_PASSWORD . '=?';
            $stmt = DatabaseConnection::mysqli()->prepare($sql);
            $stmt->bind_param('ssss', $this->getValueForKey(self::NAME_NAME), $this->getValueForKey(self::NAME_PASSWORD),
                $this->getValueForKey(self::NAME_NAME), $this->getValueForKey(self::NAME_PASSWORD));
            $stmt->execute();
        }
    }

    private
    static function hashPassword($password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    protected
    static function getCreateSql(): string {
        return 'CREATE TABLE IF NOT EXISTS ' . self::getTableName() .
            '( ' .
            self::COLUMN_ID . ' INT NOT NULL AUTO_INCREMENT ,  ' .
            self::COLUMN_NAME . ' VARCHAR(20) NOT NULL , ' .
            self::COLUMN_PASSWORD . ' VARCHAR(60) NOT NULL ,' .
            'PRIMARY KEY (' . self::COLUMN_ID . '),   UNIQUE KEY ' . self::COLUMN_NAME . '(' . self::COLUMN_NAME . ')) ENGINE = InnoDB;';
    }

    public static function create($data): void {
        // TODO: Implement create() method.
    }
}