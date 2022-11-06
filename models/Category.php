<?php

// модель для работы с категориями
class Category
{

    public static function getCategoriesList()
    {

        $db = Db::getConnection();
        $sql = 'SELECT id, name FROM category WHERE status = "1" ORDER BY sort_order, name ASC';

        $result = $db->prepare($sql);

        $result->execute();

        // получение и возврат результатов. перевод в массив
        $i = 0;
        $categoryList = array();
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;

    }

}