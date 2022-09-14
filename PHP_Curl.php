 public function validPopup($tag_id)
    {
        // return $this->model->find($tag_id);
        // $flight = Hospital::where('tag_id',  $tag_id)->first(); 
        $flight = DB::table('hospitals')->where('tag_id',  $tag_id)->get(); 
        // return $flight;
        // $url = "http://quotes.rest/qod.json";
        $url = 'https://coingecko.p.rapidapi.com/coins/markets';
        $coinType = "ardana, bitcoin, cardano, centrality, chiliz, cosmos, binancecoin, ethereum, iostoken, iris-network, jasmycoin, ontology, orchid-protocol, polkadot, solana, tezos, tron, ripple";
        $priceData[] = array();

        $parameters = [
            'vs_currency' => 'jpy',
            'ids' => $coinType
        ];

        $headers = [
            // 'Accepts: application/json',
            // 'X-CMC_PRO_API_KEY: 715ca7b7-81fa-4f8f-8f21-772f4c98ca4d'
            'x-rapidapi-key: fa4b0ae5afmsh033be1fe52ab04ap1462fcjsnc37b66987d8e'
            // "X-RapidAPI-Host: coingecko.p.rapidapi.com",
            // "X-RapidAPI-Key: 1249e67300mshd3e4acf861f194ep17b63ejsnd4568b08c77f"
            // "X-RapidAPI-Host: coingecko.p.rapidapi.com",
            // "X-RapidAPI-Key: 1249e67300mshd3e4acf861f194ep17b63ejsnd4568b08c77f"
        ];

        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL

        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        
        $json_response = json_decode($response);
        for ($i=0; $i < 18; $i++) {
            $currName = $json_response[$i]->id;
            $value = $json_response[$i]->current_price;
            $priceData[$currName] = $value;
        }

        curl_close($curl); // Close request
        return $json_response;
    }

    public function callAPI($method, $url, $data){
        $curl = curl_init();
        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'APIKEY: 111111111111111111111',
           'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
     }