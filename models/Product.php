<?php

// модель для работы с продуктами
class Product
{
    
    // кол-во отображаемых товаров по умолчанию
    const SHOW_BY_DEFAULT = 9;

    // функц. для отображ последних продуктов
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {

        $count = intval($count);

        $db = Db::getConnection();
        $sql = 'SELECT id, name, price, is_new FROM product WHERE status = "1" ORDER BY id DESC LIMIT :count';

        $result = $db->prepare($sql);
        $result->bindParam(':count', $count, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        // получение и возврат результатов (продукт)
        $i = 0;
        $productsList = array();
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productsList;
    }

    public static function getProductsListByCategory($categoryId = false)
    {

        if ($categoryId) {
            $count = Product::SHOW_BY_DEFAULT;

            $db = Db::getConnection();
            $sql = 'SELECT id, name, price, is_new FROM product WHERE status = 1 AND category_id = :category_id 
            ORDER BY id ASC LIMIT :count';

            $result = $db->prepare($sql);
            $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $result->bindParam(':count', $count, PDO::PARAM_INT);
            $result->bindParam(':offset', $offset, PDO::PARAM_INT);

            $result->execute();

            $i = 0;
            $products = array();
            while ($row = $result->fetch()) {
                $products[$i]['id'] = $row['id'];
                $products[$i]['name'] = $row['name'];
                $products[$i]['price'] = $row['price'];
                $products[$i]['is_new'] = $row['is_new'];
                $i++;
            }

            return $products;
        }
    }

    // возврат продукт с указанным ID
    public static function getProductById($id)
    {
        
        $db = Db::getConnection();
        $sql = 'SELECT * FROM product WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $result->execute();

        return $result->fetch();
    }


}