<?php


use Artapamudaid\GeminiHelper\GeminiHelper;

class Tes extends CI_Controller {

    public function tes_wa(){
        $this->load->helper('send');

        $phone = '6283851378225';
        $pesan = 'Halo Arta';
        $key = 'fce9862deac8554e58028c35011e14214b47496d05fbd61c';
        $sender = '';

        send_whatsapp($phone, $pesan, $key, $sender);
    }

    public function tes_gemini(){

        $apiKey = 'AIzaSyCROshwLbHQcUF9HJQE59jthRl2PgwikF4';

        try {
            $gemini = new GeminiHelper($apiKey);

            // Generate Text
            $result = $gemini->generateText('Tuliskan 3 tips untuk belajar PHP.');
            $output = $gemini->convertToHtml($result);
            echo nl2br($output);

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
            exit;
    }


    public function create_summary()
    {
        $this->load->helper('n8n');

        $url =  'http://localhost:5678/webhook-test/ai-summary';
        
        $data = [
                    [
                        "nama" => "Produk A",
                        "penjualan" => 150,
                        "stok" => 30,
                        "rating" => 4.5
                    ],
                    [
                        "nama" => "Produk B",
                        "penjualan" => 70,
                        "stok" => 90,
                        "rating" => 3.8
                    ],
                    [
                        "nama" => "Produk C",
                        "penjualan" => 200,
                        "stok" => 10,
                        "rating" => 4.9
                    ]
                ];

        $data = [
            'data' => $data,
            'command' => 'Buat ringkasan dari data berikut.'
        ];

        $auth = 'artatripamuda:ArtaArta10'; 

        $response = create_summary($url, $data, $auth);

        $data = json_decode($response, true);
        
        $response = create_summary($url, $data, $auth);
        var_dump($response); 
        exit;
$response = json_decode($response, true);
echo $response['summary'];



        
    }

    public function sendtogroup()
    {
        // Example usage of the woowa method

        $is_group = true;
        $receiver = ($is_group ? '120363033023493889@g.us' : '6283851378225');
        $key = 'fce9862deac8554e58028c35011e14214b47496d05fbd61c';
        $message = 'Hello, this is a test message!';
        $res = $this->woowa($receiver, $key, $message, $is_group);

        echo $res;
    }

    public function get_group_id(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://notifapi.com/get_group_id',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{
            "key": "adpe7744c3e-01a2-4734-8739-d230013b01c8"
        }',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response, true);

        $results = $res['results'];

        // var_dump($results);
        // exit;

        foreach ($results as $result) {
            if (!isset($table_started)) {
                echo "<table border='1'>
                    <tr>
                        <th>Group ID</th>
                        <th>Group Name</th>
                    </tr>";
                $table_started = true;
            }
            echo "<tr>
                <td>" . $result['id'] . "</td>
                <td>" . $result['subject'] . "</td>
            </tr>";
            if ($result === end($results)) {
                echo "</table>";
            }
        }
    }

    private function woowa($receiver = NULL, $key = NULL, $message = NULL, $is_group = NULL)
    {
        // This is a test method to send a message to Woowa API
        if ($receiver === NULL || $key === NULL || $message === NULL) {
            return "Please provide receiver, key, and message.";
            exit;
        }

        if($is_group) {
            $data = array(
                "group_id" => $receiver,
                "key" => $key,
                "message" => $message
            );
        } else {
            $data = array(
                "phone_no" => $receiver,
                "key" => $key,
                "message" => $message
            );
        }

        $jsonData = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://notifapi.com/send_message_group_id',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }
}