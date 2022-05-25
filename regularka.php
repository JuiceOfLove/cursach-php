<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

        $str = $_POST['chat'];
        if ($str == ''){
            header('Location: ./chat.php');
            exit;
        }
        $str_array = explode(" ", $str);
        //var_dump($str_array);
        //echo '<br>';
        $find = preg_grep('/#[^\s]+/', $str_array);
        if (count($find) == 0) {
            header('Location: ./chat.php');
            exit;
        }
        // var_dump($find);
        // echo '<br>';
        $find_str = implode(' ', $find);
        $final_str = preg_replace('/#/', '' ,$find_str);
        $final_array = explode(" ",$final_str);
        // var_dump($final_array);
        $final = [];

        $mysql = mysqli_connect('localhost', 'root', '', 'sorter');
        if (mysqli_connect_error()) echo 'Ошибка подключения к БД'. mysqli_connect_error();


        $unique = 'SELECT `name` as name FROM `#`';
        $unique_res = mysqli_query($mysql,$unique);

        $row = [
            'name' => '',
        ];

        $row_elements = [];

        while($row = mysqli_fetch_assoc($unique_res)){

            array_push($row_elements, $row['name']);

        }


        $rows_intersect = array_intersect($row_elements, $final_array);
        // var_dump($rows_intersect);
        // echo '<br>';

         if (count($rows_intersect) > 0) {

         $final = array_diff($final_array, $rows_intersect);

        //  var_dump($final);
        //  var_dump(count($final));

         if (count($final) === 0) {
            header('Location: ./chat.php');
            exit;
         }

         $final = implode(" ",$final);
         $final = explode(" ",$final);

        for ($i = 0; $i < count($final); $i++){

            $sql = 'INSERT INTO `#`(`name`, `data`) VALUES ("'.htmlspecialchars($final[$i]).'","'.htmlspecialchars(date(format:'Y-m-d H:i:s')).'")';
            mysqli_query($mysql,$sql);

            header('Location: ./chat.php');

        }


        }else {
            for ($i = 0; $i < count($final_array); $i++){

                $sql = 'INSERT INTO `#`(`name`, `data`) VALUES ("'.htmlspecialchars($final_array[$i]).'","'.htmlspecialchars(date(format:'Y-m-d H:i:s')).'")';
                mysqli_query($mysql,$sql);

                header('Location: ./chat.php');
            }
        }



    ?>
</body>
</html>