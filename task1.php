<?php

function reshapeCSVMatrix( 

            /* Путь к исходному файлу данных и к результирующей матрице */
            $sourceDataPath = "./task_1/source__300.csv",       
            $resultDataPath = "./task_1/result__300.csv",

            /* Размерность матрицы */
            $matrixSizeX = 300,                                 
            $matrixSizeY = 300,

            /* Разделитель в CSV для исходных данных и результата */
            $sourceDelim = '|',                                 
            $resultDelim = ',') {

    $source = fopen( $sourceDataPath , "r");

    /*  Проверка на корректное открытие исходного файла и простая валидация аргументов */
    if( $source === false 
        || !is_int($matrixSizeX) 
        || !is_int($matrixSizeY) 
        || !$sourceDelim 
        || !$sourceDataPath 
        || !$resultDataPath
        || !$resultDelim )
        
        return false;

    $result = fopen( $resultDataPath, 'w' );
    if( $result === false ) return false;

    /*  Вместо матрицы использован одномерный массив, чтобы не использовать 
        двойное обращение по индексу и деление по модулю на каждой итерации цикла */
    $resultMatrix = array_fill(0, $matrixSizeX * $matrixSizeY, 0);


    /*  Заполнение соответствующих ячеек матрицы с учетом смещения */
    while( ($data = fgetcsv($source, 100, $sourceDelim)) !== false ) { 
        $resultMatrix[ $data[0] - 1] = $data[1];
    }

    /*  Исходный файл прочитан и разобран, необходимости в нем больше нет */
    fclose($source);

    /* Вместо преобразования через array_chunks использован array_slice с итерированием смещения */
    for($i=0; $i < $matrixSizeY; $i++) {
        $row = implode( $resultDelim, array_slice($resultMatrix, $i * $matrixSizeX, $matrixSizeX));
        fwrite($result, $row . PHP_EOL );
    }
    fclose($result);

    return true;
}


reshapeCSVMatrix();

?>
