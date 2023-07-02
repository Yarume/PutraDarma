<?php 

class Model_Telegram extends CI_Model{

    public function User_Access($telegramid)
    {
        $this->db->select('*')
        ->from('users')
        ->join('user_group','users.id = user_group.user_id')
        ->where('users.TelegramID', $telegramid)
        ->where('user_group.group_id', 1);
        return $this->db->get();
    }
    public function databarang($brandid){
        $this->db->select('*')
        ->from('products')
        ->like('brand_id', $brandid)
        ->where('availability', 1);
        return $this->db->get();
    }
    public function laporanbarang(){
        $this->db->select('*')
        ->from('products')
        ->where('availability', 1);
        return $this->db->get();
    }
    
    public function sold_items($productid){
        $this->db->select('*')
        ->from('orders_item')
        ->where('product_id', $productid);
        return $this->db->get();
    }

    public function orderitems(){
        $this->db->select('*')
        ->from('orders_item')
        ->select(array('products.id as id',
            'products.name as name',
            'orders_item.qty as qty',
            'orders_item.amount as amount',
            'orders.date_time as time'))
        ->join('products','orders_item.product_id = products.id')
        ->join('orders','orders_item.order_id = orders.id');
        return $this->db->get();  
    }

    public function history($tgl1,$tgl2){
        $this->db->select('*')
        ->from('history')
        ->select('products.id as id')
        ->select('products.name as name')
        ->select('history.qty as quantity')
        ->where('history.date >=', $tgl1)
        ->where('history.date <=', $tgl2)
        ->join('products','history.products = products.id');
        return $this->db->get(); 
    }
}

//SELECT * FROM `history` WHERE `date` BETWEEN '2023-06-01' AND '2023-06-30'