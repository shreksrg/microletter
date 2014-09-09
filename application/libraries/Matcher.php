<?php

class Matcher extends CCApplication
{
    static function matchOrigin($value)
    {
        static $rows = array();
        if (!$rows) {
            $modelGoods = CModel::make('goods_model');
            $rows = $modelGoods->getOrigin();
        }
        if ($rows) {
            foreach ($rows as $row) {
                if ($row['value'] == $value) return $row['name'];
            }
        }
        return '';
    }
}