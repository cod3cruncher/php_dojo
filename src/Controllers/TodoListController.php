<?php


namespace PHPDojo\Controllers;

use PHPDojo\Helpers\TemplateRenderer;
use PHPDojo\Models\TodoList;
use PHPDojo\Models\TodoListItem;
use PHPDojo\Models\User;

/**
 * Class TodoListController
 * Here is all the logic for the TodoList
 * New Lists can be created, renamed or deleted
 * and you can add new items to a list or remove items
 * @package PHPDojo\Controllers
 */
class TodoListController extends CrudController
{
    public const UPDATE_ACTION_ADD_ITEM = 'add_item';
    public const UPDATE_ACTION_REMOVE_ITEM = 'remove_item';

    public function index(): void {
        echo TemplateRenderer::render('Dashboard.simptemp', [
            'user' => $_SESSION['user'],
            'todoLists' => TodoList::allForUser($_SESSION['user'])
        ], false);
    }

    public function doCreate() {
        if (isset($_GET['name'])) {
            $data = array();
            $data['name'] = $_GET['name'];
            $data['user'] = $_SESSION['user'];
            $todoList = new TodoList();
            $todoList->setName($_GET['name']);
            $todoList->setUser($_SESSION['user']);
            $todoList->save();
            header('Location: /todolist');
        }
    }

    public function doStore() {
        // TODO: Implement doStore() method.
    }

    /**
     * Shows the list for the id
     * @param $id
     */
    public function doShow($id) {
        echo TemplateRenderer::render('Dashboard.simptemp', [
            'user' => $_SESSION['user'],
            'todoLists' => TodoList::allForUser($_SESSION['user']),
            'todoListId' => $id
        ], false);
    }

    public function doEdit($id) {
        if(isset($_GET['newname']) || (strlen($_GET['newname']) > 0))  {
            $list = TodoList::find($id);
            $list->setName($_GET['newname']);
            $list->save();
            header('Location: /todolist/' . $id);
        }
        else {
            throw new \Exception("newname was not set or is empty!");
        }
    }

    public function doUpdate() {
        if (isset($_POST['updateAction'])) {
            $updateAction = $_POST['updateAction'];
            switch ($updateAction) {
                case self::UPDATE_ACTION_ADD_ITEM:
                    if (isset($_POST['itemName'])) {
                        $name = trim($_POST['itemName']);
                        if (isset($_POST['todoListId'])) {
                            $listId = $_POST['todoListId'];
                            $this->addItemToList($name, $listId);
                        }
                        else {
                            throw new \Exception('todoListId was not set!');
                        }
                    }
                    else {
                        throw new \Exception("itemName was not set!");
                    }
                    break;
                case self::UPDATE_ACTION_REMOVE_ITEM:
                    if (isset($_POST['todoListIdItem'])) {
                        $itemId = $_POST['todoListIdItem'];
                            $this->removeItemFromList($itemId);
                    }
                    else {
                        throw new \Exception('todoListIdItem was not set!');

                    }
                    break;
            }
        }
    }

    public function doDelete() {
        if (isset($_GET['todoListDeleteId'])) {
            $data = array();
            $data['todoListDeleteId'] = $_GET['todoListDeleteId'];
            $data['user'] = $_SESSION['user'];
            TodoList::delete($data);
            header('Location: /todolist');
        }
        exit;
    }

    public function isLoginNeeded(): bool {
        return true;
    }

    private function addItemToList($itemName, $listId) {
        $list = TodoList::find($listId);
        $item = new TodoListItem();
        $item->setTodoListId($list->getId());
        $item->setTitle($itemName);
        $list->addItem($item);
        $list->save();
        header('Location: /todolist/' . $listId);
    }

    private function removeItemFromList($todoListItemId) {
        $item = TodoListItem::find($todoListItemId);
        $list = TodoList::find($item->getTodoListId());
        $list->removeItem($item);
        $list->save();
        header('Location: /todolist/' . $list->getId());
    }
}