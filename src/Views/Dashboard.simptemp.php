<!doctype html>
<html lang="en">
<head>
    <title>To-Do</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/styles.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<body>
<div class="container">
    <div class="head">
        <h1>To-Do</h1>
        <div class="button">
            <a href="/logout" class="btn btn-secondary btn-lg" role="button">LOGOUT [<?= $user->getName() ?>]</a>
        </div>
    </div>

    <div>
        <p class="meine-listen"><strong>Meine Listen:</strong></p>
        <div class="list-group grid">
            <?php
            foreach ($todoLists as $todoList) {
                if (isset($todoListId) && $todoList->getId() == $todoListId) {
                    echo '<div class="list-group-item d-flex" style="background-color:lightblue">
                            <a class="list-group-item-action active" href="/todolist/' . $todoList->getId() . '">'
                                . $todoList->getName() . '</a>
                            <form action="/todolist/delete" method="get">
                                <input type="hidden" id="todoListDeleteId" name="todoListDeleteId" value="' . $todoListId . '"> 
                                <button type="submit" class="btn btn-secondary btn-sm" role="button">Löschen</button>
                            </form>
                            <form action="/todolist/' . $todoListId . '/edit" method="get">
                                <div class="form-group">
                                    <button id="edit" type="submit" class="btn btn-secondary btn-sm" role="button">Umbenennen</button>   
                                    <input type="text" class="form-control" name="newname" id="newname" placeholder="Neuer Name" required>
                                </div>
                            </form>
                         </div>';
                }
                else {
                    echo '<a class="list-group-item list-group-item-action" href="/todolist/' . $todoList->getId() . '">' . $todoList->getName() . '</a>';
                }
            }
            ?>
            <form class="list-group-item" action="/todolist/create" method="get">
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Neue Liste" required>
                </div>
                <button type="submit" class="btn btn-secondary btn-sm" role="button">Anlegen</button>
            </form>
        </div>
    </div>
    <div class="grid">
        <ul class="list-group">
            <?php
            if (isset($todoListId)) {
                foreach ($todoLists as $todoList) {
                    if ($todoList->getId() == $todoListId) {
                        $todoItems = $todoList->getItems();
                        foreach ($todoItems as $todoItem) {
                            echo '<li class="list-group-item">
                                <div>
                                <form action="/todolist/update" method="post">
                                <div class="form-group">'
                                  . $todoItem->getTitle() .
                                    '<button id="edit" type="submit" class="btn btn-secondary btn-sm" style="float: right" role="button">Entfernen</button>   
                                    <input type="hidden" id="updateActionItem" name="updateAction" value="remove_item">
                                    <input type="hidden" id="todoListId2" name="todoListId" value="' . $todoListId .'">
                                    <input type="hidden" id="todoListIdItem2" name="todoListIdItem" value="' . $todoItem->getId() .'">
                                </div>
                            </form>
                            </div>  
                              </li>';
                        }
                    }
                }
            }
            ?>
                <?php if(isset($todoListId)) {
                echo '
            <li>
            <form class="list-group-item" action="/todolist/update" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="itemName" id="itemName" placeholder="Neuer Eintrag" required>
                    <input type="hidden" id="updateACtion" name="updateAction" value="add_item">
                    <input type="hidden" id="todoListId" name="todoListId" value="' . $todoListId .'">
                    <button type="submit" class="btn btn-secondary btn-sm" role="button">Hinzufügen</button>
                </div>
            </form>
            </li> ';
                }
                ?>
        </ul>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#edit').on('click', function () {
            myApp.confirm('Are you sure?', 'Title', function () {
                $('.btn-no').text("No");
                $('.btn-yes').text("Yes");
            });
        });
</script>

</body>
</html>