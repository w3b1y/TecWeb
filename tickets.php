<?php
session_start();

require_once "funzioni.php";
require_once "DBAccess.php"; 

$fileHTML = file_get_contents("tickets.html");

use DB\DBAccess;

$connessione = new DBAccess();
$connessione->openDBConnection();

if(isset($_SESSION['ricerca'])){

    $stazionePartenza = $_SESSION['ricerca']['from'];
    $stazioneArrivo = $_SESSION['ricerca']['to'];
    $dataOra = $_SESSION['ricerca']['date'];
    $nPasseggeri = $_SESSION['ricerca']['seats'];
    $discount_code = isset($_SESSION['ricerca']['discount_code']) ? $_SESSION['ricerca']['discount_code'] : 0;
    $qResult_discount = $connessione->getDataArray("select offers.discount from offers where offers.discount_code='$discount_code' and offers.final_date >= curdate()");
    $discount = empty($qResult_discount) ? 0 : $qResult_discount[0];

    $route = '<h3 class="container__heading">'.$stazionePartenza.' - '.$stazioneArrivo.'</h3>';
    $fileHTML = str_replace("<tratta/>", $route, $fileHTML);

    $dateTime = new DateTime($dataOra);

    $qResult_route = $connessione->getDataArray("select start.route_id from route_station as start, route_station as end 
        where start.station_id='$stazionePartenza' and end.station_id='$stazioneArrivo' and start.route_id=end.route_id");

    $ticket = "";

    if (empty($qResult_route)) {
        $fileHTML = str_replace("<biglietti/>", "<p class=\"container__heading\">Attualmente Iberu Trasporti non offre la tratta da lei cercata,
         ci scusiamo per il disagio</p>", $fileHTML);
        echo $fileHTML;
        exit();
    }

    $counter = 0;
    $total_routes = count($qResult_route);
    $counter_routes = 0;
    foreach($qResult_route as $ris){
        $qResult_price = $connessione->getDataArray("select start.price - end.price from route_station as start join route_station as end on 
            start.route_id = end.route_id where start.station_id = '$stazionePartenza' and end.station_id = '$stazioneArrivo' and start.route_id = $ris");
        $qResult_price[0] = number_format(($qResult_price[0] - $qResult_price[0] * $discount / 100) * $nPasseggeri, 2);
        $qResult_time = $connessione->getDataArray("select route_station.duration from route_station where route_station.route_id=$ris 
            and route_station.station_id='$stazionePartenza'");
        $departure_time_route = $dateTime->sub(getDateInterval($qResult_time[0]));
        $qResult_duration = $connessione->getDataArray("select timediff(end.duration, start.duration) as time_difference
            from route_station as start join route_station as end on start.route_id = end.route_id
            where start.station_id = '$stazionePartenza' and end.station_id = '$stazioneArrivo'");
        $qResult_departure = $connessione->getDataArray("select route_schedule.id, route_schedule.departure_time from route_schedule where route_schedule.route_id=$ris 
            and route_schedule.departure_time >= '".$departure_time_route->format('H:i')."'");

        if (empty($qResult_departure)) {
            $fileHTML = str_replace("<biglietti/>", "<p class=\"container__heading\">Nessun biglietto disponibile, prego cambiare la data</p>", $fileHTML);
            echo $fileHTML;
            exit();
        }

        $total_departures = count($qResult_departure);
        $counter_departures = 0;
        foreach ($qResult_departure as $dt) {
            $departure_time_station = (new DateTime($dt['departure_time']))->add(getDateInterval($qResult_time[0]));
            $arrive_time_station = clone $departure_time_station; 
            $arrive_time_station->add(getDateInterval($qResult_duration[0]));
            $train_id = $connessione->getDataArray("select route_schedule.train_id from route_schedule where route_schedule.route_id=$ris 
                and route_schedule.departure_time = '".$dt['departure_time']."'");

            $ladder = "";
            $route_stations = $connessione->getDataArray("select * from route_station where route_station.route_id=$ris");
            $total_stations = count($route_stations);
            $counter_stations = 0;
            $previous_time = null;
            foreach ($route_stations as $rs) {
                $time = new DateTime($dt['departure_time']);
                $time->add(getDateInterval($rs["duration"]));
                if ($counter_stations == 0) {
                    $ladder = $ladder . '<dt class="route__term--vertical">'.$rs["station_id"].'</dt>
                    <dd class="route__data--vertical"><time datetime="'.$time->format('H:i').'">'.$time->format('H:i').'</time></dd>';
                }
                else {
                    $duration = new DateTime($rs["duration"]);
                    $duration->sub(getDateInterval($previous_time));
                    $ladder = $ladder . '<dt class="route__term--vertical vline">Durata</dt>
                    <dd class="route__data--vertical"><time datetime="'.
                    $duration->format('H:i').'">'.$duration->format('H:i').'</time></dd>
                    <dt class="route__term--vertical">'.$rs["station_id"].'</dt>
                    <dd class="route__data--vertical"><time datetime="'.$time->format('H:i').'">'.$time->format('H:i').'</time></dd>';
                }
                $counter_stations++;
                $previous_time = $rs["duration"];
            }

            $ticket .= '<article id="'.$counter.'" class="ticket js-ticket">
                        <dl class="ticket__route--horizontal">
                        <dt class="route__term--horizontal">'.$stazionePartenza.'</dt>
                        <dd class="route__data--horizontal"><time datetime="'.
                        $departure_time_station->format('H:i').'">'.$departure_time_station->format('H:i').'</time></dd>
                        <dt class="route__term--horizontal route__term--line">Durata</dt>
                        <dd class="route__data--horizontal route__data--line"><time datetime="'.
                        $qResult_duration[0].'">'.$qResult_duration[0].'</time></dd>
                        <dt class="route__term--horizontal">'.$stazioneArrivo.'</dt>
                        <dd class="route__data--horizontal"><time datetime="'.
                        $arrive_time_station->format('H:i').'">'.$arrive_time_station->format('H:i').'</time></dd>
                        </dl>
                        <a href="#'.(($counter_departures==$total_departures - 1 && $counter_routes==$total_routes - 1) ? 0 : ($counter + 1)).
                        '" class="visually-hidden">Prossimo biglietto</a>
                        <div class="ticket__body js-ticket__body ticket__body--reduced">
                        <dl class="ticket__route--vertical">'.
                        $ladder
                        .'</dl>
                        <div class="ticket__description">
                            <p class="ticket__content">Data: '.$dateTime->format("d/m/y").'</p>
                            <p class="ticket__content">Identificativo treno: '.$train_id[0].'</p>
                            <p>Scegli la classe:</p>
                            <button class="ticket__class js-first__class ticket__class--selected">Prima classe</button>
                            <button class="ticket__class js-second__class">Seconda classe</button>
                        </div>
                        </div>
                        <div>
                        <button aria-label="Espandi la notizia" class="ri-arrow-down-s-line news__expand js-news__expand"></button>
                        <button data-route="'.$ris.'" data-schedule="'.$dt['id'].'" data-date="'.$dateTime->format("d/m/y").'"
                        data-departure="'.$stazionePartenza.'" data-arrival="'.$stazioneArrivo.'"
                        class="submit submit--ticket js-submit" data-firstClass="'.$qResult_price[0].'" data-secondClass="'
                        .number_format($qResult_price[0] * 0.8, 2).'">€'.$qResult_price[0].'</button>
                        </div>
                    </article>';
            $counter++;
            $counter_departures++;
        }
        $counter_routes++;
    }

} else {
    echo "Dati non disponibili.";
}
$fileHTML = str_replace("<biglietti/>", $ticket, $fileHTML);
echo $fileHTML;
?>