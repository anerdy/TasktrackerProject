<?php
namespace App\Services;

use App\Models\Model_Task;

class Task_Service
{

    public function validateEditTask()
    {
        $errors = [];
        if ( ! isset($_POST['text']) || empty($_POST['text']) )
            $errors['text'] = 'Ошибка валидации поля text';

        $allowed_statuses = ['1','0'];
        var_dump(in_array($_POST['status'], $allowed_statuses));
        if ( ! isset($_POST['status']) || !in_array($_POST['status'], $allowed_statuses))
            $errors['status'] = 'Ошибка валидации поля status';

        return $errors;
    }

    public function validateTask()
    {
        $errors = [];
        if ( ! isset($_POST['name']) || empty($_POST['name']) || strlen($_POST['name']) > 100 )
            $errors['name'] = 'Ошибка валидации поля name';
        if ( ! isset($_POST['email']) || empty($_POST['email']) || strlen($_POST['email']) > 255 )
            $errors['email'] = 'Ошибка валидации поля email';
        if ( ! isset($_POST['text']) || empty($_POST['text']) )
            $errors['text'] = 'Ошибка валидации поля text';

        // Check if file was uploaded without errors
        if ( isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
            $filename = $_FILES["image"]["name"];
            $filetype = $_FILES["image"]["type"];
            $filesize = $_FILES["image"]["size"];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!array_key_exists($ext, $allowed)) $errors['image'] = 'Неверный формат файла';

            // Verify file size - 3MB maximum
            $maxsize = 3 * 1024 * 1024;
            if($filesize > $maxsize) $errors['image'] = 'Максимальный размер файла 3 Мб';

            // Verify MYME type of the file
            if (in_array($filetype, $allowed)) {
                // Check whether file exists before uploading it
                if ( file_exists("images/" . $filename)) {
                    $errors['image'] = 'Файл уже существует';
                } else {
                    // Можно загружать
                }
            } else {
                $errors['image'] = 'Неверный формат файла';
            }
        } else {
            $errors['image'] = 'Ошибка валидации поля image';
        }


        return $errors;
    }

    public function createTask()
    {
        $newTask = new Model_Task();
        $newTask->name = $_POST['name'];
        $newTask->email = $_POST['email'];
        $newTask->text = $_POST['text'];

        $filename = $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $filename);
        $newTask->image = "images/" . $filename;

        $newTask->save();
    }

    public function editTask($task)
    {
        $task->text = $_POST['text'];
        $task->status = $_POST['status'];
        $task->save();
    }


}
