<?php
  function POST_curl($client_id, $email, $name) {
    $api_url = 'https://api.supermetrics.com/assignment/register';
    $curl = curl_init($api_url);
    $curl_post_data = array(
            'client_id' => $client_id,
            'email'     => $email,
            'name'     => $name,
    );
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('curl exec error: ' . var_export($info));
    }
    curl_close($curl);
    $decoded = json_decode($curl_response);
    if (isset($decoded->{'error'})) {
        die('POST error occured: ' . $decoded->{'error'}->{'message'});
    }
    return $decoded;
  }

  function GET_curl($sl_token, $page) {
    $api_url = 'https://api.supermetrics.com/assignment/posts?sl_token='.$sl_token.'&page='.$page.'';
    $curl = curl_init($api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('GET error occured: ' . var_export($info));
    }
    curl_close($curl);
    $decoded = json_decode($curl_response); //meta ,data
    if (isset($decoded->{'error'})) {
        die('GET error occured: ' . $decoded->{'error'}->{'message'});
    }
    return $decoded;
  }

/////////////////////////////////////
/*    RETURN
- Average character length / post / month
- Longest post by character length / month
- Total posts split by week
- Average number of posts per user / month
*/

  $sl_token = POST_curl($_POST['client_id'], $_POST['email'], $_POST['name'])->{'data'}->{'sl_token'};
  $week=0;
  $month_id=0;
  $month = 0;
  $week_stat[0] = 0;

for( $i = 1; $i<11; $i++ ) {
  $decoded = GET_curl($sl_token, $i);
  $decoded_2 = get_object_vars($decoded->{'data'}); // //stdClass::__set_state

  foreach ($decoded_2['posts'] as $post) {
    $decoded_3 = get_object_vars($post);

    $message_nr[$month][$month_id++] = strlen($decoded_3['message']);
    $date = new DateTime($decoded_3['created_time']);

    $user_id = strtok($decoded_3['from_id'], 'user_');
    if(!isset($user[$month][$user_id])) $user[$month][$user_id] = 0;
    $user[$month][$user_id]++;

    $week_stat[$week]++;

    if($date < new DateTime("-".($month+1)." month")){
      $month_stat[$month]['max'] = max($message_nr[$month]);
      $month_stat[$month]['average'] = number_format(array_sum($message_nr[$month]) / count($message_nr[$month]), 2, '.', '');
      $month++;
      $month_id=0;
    }

    if($date < new DateTime("-".($week+1)." week")){
      $week++;
      $week_stat[$week] = 0;
    }
  }
//left last month
  $month_stat[$month]['max'] = max($message_nr[$month]);
  $month_stat[$month]['average'] = number_format(array_sum($message_nr[$month]) / count($message_nr[$month]), 2, '.', '');
}

$data = array();
$data['month_stat'] = $month_stat;
$data['week_stat'] = $week_stat;
//$data['month_user'] = $user; // get error array id
$p = 0;
foreach ($user as $post) {
  $z=0;
  foreach ($post as $pos) {
    $data['month_user'][$p]['user_'.$z] = $pos;
    $z++;
  }
  $p++;
}

echo json_encode($data, JSON_FORCE_OBJECT);
 ?>
