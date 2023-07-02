<?php

include ('./Telegram.php');


class TelegramBot extends Admin_Controller {

    protected $telegram;

	public function __construct()
	{
		parent::__construct();
        $this->telegram = new Telegram('');
        $this->load->model('Model_Telegram');
	}
    public function index(){
        $split = explode(' ', $this->telegram->Text());
        $teleid = $this->telegram->userid();
        $teleidcheck = $this->Model_Telegram->User_Access($teleid)->row();
        if($teleidcheck){

            $callback_query = $this->telegram->Callback_Query();
            if (!empty($callback_query)) {


                //RandomArray Databarang
                $input = array("polo", "adidas", "nike");
                $rand_keys = array_rand($input, 2);
                //Callback Menu 
                if ($this->telegram->Callback_Data()        == "databarang")                { $this->send_message($this->databarang($input[$rand_keys[0]])); }
                else if ($this->telegram->Callback_Data()   == "laporandatabarang")         { $this->laporandatabarang(); }
                else if ($this->telegram->Callback_Data()   == "pembelianbarang")           { $this->pembelianbarang(); }


            }

            if (strtolower($split[0]) == "/start") {
                $startt = "Selamat datang di Putra Darma Telegram Bot \n\nBot ini dirancang untuk mempermudah admin / owner untuk melihat laporan dan databarang dan juga dapat di export ke excel \n\n--- FITUR ---\n/databarang - Untuk melihat list data barang yang terdapat di dalam gudang, contoh : /databarang (polo/nike/adidas) \n/laporandatabarang - untuk memunculkan laporan barang masuk dan keluarnya barang setiap Bulannya \n /pembelianbarang - Untuk memunculkan laporan hasil pembelian barang pada tanggal tertentu contoh : 2023-01-25/2023-02-25";
                $option = array( 
                array(
                    $this->telegram->buildInlineKeyBoardButton("Data Barang",                   $url="", $callback_data = 'databarang')),
                array( 
                    $this->telegram->buildInlineKeyBoardButton("Laporan Data Barang",           $url="", $callback_data = 'laporandatabarang'), 
                    $this->telegram->buildInlineKeyBoardButton("Pembelian Barang",              $url="", $callback_data = 'pembelianbarang')) 
                );
                $keyb = $this->telegram->buildInlineKeyBoard($option);
                $content = array('chat_id' => $this->telegram->ChatID(), 'reply_markup' => $keyb, 'text' => $startt);
                $this->telegram->sendMessage($content);
            }
            $this->fitur();
        }else{
            $this->send_message("Telegram ID ".$teleid." tidak terdaftar dalam Server");
        }
    }
    
    private function fitur(){
        $split = explode(' ', $this->telegram->Text());
        switch (strtolower($split[0])) {
            case '/databarang':
                $this->databarang($split[1]);
            break;
            
            case '/laporandatabarang':
                $this->laporandatabarang();
            break;
            
            case '/pembelianbarang':
                $this->pembelianbarang();
            break;
           
        }

    }

    private function send_message($text){
        $content = array(
            'chat_id' => $this->telegram->ChatID(), 
            'text' => $text,
            'reply_to_message_id' => $this->telegram->MessageID());
        return $this->telegram->sendMessage($content); 
    }

    public function export($tipe){
        $expType = explode(".", $type); 
        switch ($expType) {
            case 'laporan_data_barang':
                $data['items'] = $this->Model_Telegram->laporanbarang()->result();
                $data['type'] = $expType[0];
                $data['ekstensi'] = $expType[1];
                $this->load->view('export/data_stok', $data);
            break;

            case 'pembelian_barang':
                $data['items'] = $this->Model_Telegram->orderitems()->result();
                $data['type'] = $expType[0];
                $data['ekstensi'] = $expType[1];
                $this->load->view('export/data_stok', $data);
            break;
            
        }
    }
    private function databarang($brandid){
        if ($brandid == NULL) {
            $this->send_message("/databarang - Untuk melihat list data barang yang terdapat di dalam gudang, contoh : /databarang (polo/nike/adidas)");
        }
        $telegram = "---[ Data Barang ]---\n";
        $telegram .="/databarang - Untuk melihat list data barang yang terdapat di dalam gudang, contoh : /databarang (polo/nike/adidas)\n\n";
        if (strtolower($brandid) == "polo") $brand     = 5;
        if (strtolower($brandid) == "nike") $brand     = 6;
        if (strtolower($brandid) == "adidas") $brand   = 7;

        foreach ($this->Model_Telegram->databarang($brand)->result() as $data) {
            $telegram .= "[ {$data->qty}x ] [ $data->name ]\n";
        }
        $this->send_message($telegram);
    }

    public function laporandatabarang(){
        $telegram  =  "Laporan Data Barang pada Bulan ".date('Y-m-d')."\n";
        $telegram .=  "[ Data Barang Masuk ] \n";
        foreach ($this->Model_Telegram->history(date('Y-m-01'), date('Y-m-t'))->result() as $data) {
            $telegram .= "[ {$data->quantity}x ] [ {$data->name} ]\n";
        }
        $telegram .=  empty($data) ? "Tidak ada Barang Masuk untuk bulan ini\n" : "";
        $telegram .=  "[ Data Barang Keluar ] \n";        
        foreach ($this->Model_Telegram->orderitems()->result() as $data1) {
            if (date('m', $data1->time) == date('m')) {
                $telegram .= "[ {$data1->qty}x ] [ {$data1->name} ]\n";
            }else{
                $telegram .= "Tidak ada Barang keluar untuk bulan ini";
            }
        }
        $this->send_message($telegram);

    }

    public function pembelianbarang(){
        $telegram = "Laporan Pembelian Barang \n";
        $telegram .= "----- \n";
        $tmpqty=$tmpamt=0;
        foreach ($this->Model_Telegram->orderitems()->result() as $data) {
            $telegram .= "[ {$data->qty}x ] [ {$data->name} ] [ Rp {$data->amount} ]";
        }
        $this->send_message($telegram);

    }

    public function testtt(){
       
    }


}
