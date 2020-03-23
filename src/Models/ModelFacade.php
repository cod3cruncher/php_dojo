<?php
namespace PHPDojo\Models;

interface ModelFacade {

    /**
     * returns all entries in the database
     * @return all entries
     */
    public static function all();

    /**
     * Returns the entry for the given id (Primary Key)
     * @param $id the id (PK)
     * @return the entry for given id
     */
    public static function find(int $id);

    /**
     * Deletes the entry with the given id (Primary Key)
     * @param $id the id (PK)
     */
    public static function delete(int $id) :void;


}
