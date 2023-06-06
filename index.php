<?php
require 'db/config.php';


if (isset($_POST['save']) && !empty($_POST['name'])) {
  $name = $_POST['name'];
  $sth = $pdo->prepare('INSERT INTO tasks(name) VALUES(?)');
  $sth->execute([$name]);

}


if(isset($_POST['delet'])){
  $delete = $_POST['id'];
  $sth = $pdo->prepare('DELETE FROM tasks WHERE id = ?'); 
  $sth->execute([$delete]); 
}

// UPDATE users SET name = ' DIORALOX' WHERE id = 7;
if(isset($_POST['changeStatus'])){
 $change = $_POST['id'];

 $status = $_POST['status'] == 1 ? 0 : 1;
      // if($_POST['status'] == 1){
      //   $status = 0;
      // } else {
      //   $status = 1;
      // }
 $sth = $pdo->prepare('UPDATE tasks SET status = ? WHERE id = ?'); 

  $sth->execute([$status, $change]); 
}


if(isset($_POST['update'])){
  $id = $_POST['id']; //переименуй переменную
  $name = $_POST['name'];

  // Отлично теперь ты видишь что данные приходят. Кнопка нажимается и мы сюда попадаем в это условие. но откуда там поле лишние
       
  $sth = $pdo->prepare('UPDATE tasks SET name = ? WHERE id = ?'); 
  $sth->execute([$name, $id]); 
 }

if (isset($_POST['surch']) && !empty($_POST['name']))  {
  $query = $_POST['name'];
  // print_r($_POST);
  $name = "%$query%";


  $sth = $pdo->prepare('SELECT * FROM tasks WHERE name LIKE ? ORDER BY id DEC');
 $sth->execute([$name]);
 $data = $sth->fetchAll();
} else {
  $data = $pdo->query('SELECT * FROM tasks ORDER BY id DESC')->fetchAll();
}


// $data = $pdo->query('SELECT * FROM tasks ORDER BY id DESC')->fetchAll();

// $sth = $pdo->prepare('SELECT * FROM tasks WHERE name = :name AND id = :id');
// $sth->execute(['name' => 'test' , 'id' => 1]);
// $tasks = 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo list</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<style>
  tr{
    vertical-align: middle;
  }
.finished{
  background-color: #bdf9bd;
}

.name-finish {
    text-decoration: line-through;
  }

</style>



<body>

<section class="vh-100" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-lg-9 col-xl-7">
        <div class="card rounded-3">
          <div class="card-body p-4">

            <h4 class="text-center my-3 pb-3">To Do App</h4>

            <form action="" class="row row-cols-lg-auto g-3 justify-content-center align-items-center mb-4 pb-2" method="post">
              <div class="col-12">
                <div class="form-outline">
                  <input type="text" name="name" id="name" class="form-control" />
                </div>
              </div>

              <div class="col-12">
                <button type="submit" name="save" class="btn btn-primary">Save</button>
              </div>

              <div class="col-12">
                <button type="submit" name="surch" class="btn btn-warning">Get tasks</button>
              </div>
            </form>

            <table class="table mb-4">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Todo item</th>
                  <th scope="col">Status</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
 <!-- кстати да, и не нужно было бы отдельно форму ещё создавать ща посмотрю -->

              <?php if(!empty($data)):  ?>
                <?php foreach ($data as $key => $task): ?>
                <tr <?= $task['status'] == 0 ? 'class="finished"' : '' ?>>

             
                  <th scope="row"><?= $key + 1 ?></th>
                  <td <?= $task['status'] == 0 ? 'class="name-finish"' : '' ?>><?= htmlspecialchars($task['name']) ?></td>
                  <td><?= $task['status'] == 1 ? 'in progress' : 'Finish' ?></td>
                  <td>
                  <form method="POST">
                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                    <input type="hidden" name="status" value="<?= $task['status'] ?>">
                    <button type="submit" name="delet" class="btn btn-danger">Delete</button>
                    <button type="submit" name="changeStatus" class="btn btn-success ms-1">
                    <?= $task['status'] == 0 ? 'No finished' : 'Finished' ?>
                    </button>
                  <?php if($task['status'] == 1): ?>
                    <button type="button" name="edit" data-toggle="modal" data-target="#exampleModal-<?= $task['id']?>" class="btn btn-warning ms-1" >
                    edit
                    </button>
                  <!-- </form> -->
                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal-<?= $task['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <!-- <form method="POST"> -->
                              <!-- всё, теперь всё что внутри формы будут в POST массиве приходить -->
                              <div class="modal-body">
                              <input type="text" name="name" value="<?= $task['name'] ?>">
                              <input type="hidden" name="id" value="<?= $task['id'] ?>">
                              <!-- Смотри, имя уже приходит но чтобы обновить на новое имя - нам нужно так же что передать, чтобы обновилась запись? Как база поймет какую запись нужно обновить если мы только передаем имя? Что ещё нужно как ты думаешь? айди ЙЕС, передай скрытый айди плиз -->
                              </div>
                              <div class="modal-footer">
                                <!-- чтобы данные приходили в POST нужно обернуть в форму эти кнопки и поля инпутов -->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="update" class="btn btn-primary">Save changes</button>
                                <!-- так тут кнопка называется "upd" лучше назову польностью -->
                            </form>
                            <!-- Форма закрывается давай попробуем -->
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endif;?>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif;  ?>
                
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>