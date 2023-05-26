<?php
/* Функция генерации календаря */
if (!function_exists('draw_Weekcalendar')) {
    function draw_Weekcalendar($month, $year, $events, $currentMondayDate)
    {
        /* Начало таблицы */
        $months = array(
            '',
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь');
        $dow = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье');
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar table js-calendar calendar-default">';
        $nMonth = $month + 1;
        $nYear = $year;
        $pYear = $year;
        if ($nMonth == 13) {
            $nMonth = 1;
            $nYear++;
        }
        $pMonth = $month - 1;
        if ($pMonth == 0) {
            $pMonth = 12;
            $pYear--;
        }
        // echo($currentMondayDate."<br>");
        $currentMondayDate = date('Y-m-d', $currentMondayDate);
        // echo($currentMondayDate."<br>");
        
        $pCurrentMondayDate = @date('Y-m-d', strtotime($currentMondayDate . ' -7 days'));
        // echo($pCurrentMondayDate." - ");
        $pCurrentMondayDateTStamp = @strtotime($pCurrentMondayDate);
        // echo($pCurrentMondayDateTStamp."<br>");
        
        $nCurrentMondayDate = @date('Y-m-d', strtotime($currentMondayDate . ' +7 days'));
        // echo($nCurrentMondayDate." - ");
        $nCurrentMondayDateTStamp = @strtotime($nCurrentMondayDate);
        // echo($nCurrentMondayDateTStamp."<br>");
        
        
        $currentMonday = $currentMondayDate;
        // echo("currentMonday - " . $currentMonday."<br>");
        $endDate = @date('Y-m-d', strtotime($currentMonday . ' +6 days'));
        $counterDays = $currentMonday;
        
        if (date('n', strtotime($currentMonday)) == date('n', strtotime($endDate))){
            $monthInHeader = $months[date('n', strtotime($currentMonday))];
        } else {
            $monthInHeader = $months[date('n', strtotime($currentMonday))] . ' - ' . $months[date('n', strtotime($endDate))];
        }
        
        if (date('Y', strtotime($currentMonday)) == date('Y', strtotime($endDate))){
            $yearInHeader = date('Y', strtotime($currentMonday));
        } else {
            $yearInHeader = date('Y', strtotime($currentMonday)) . '/' . date('Y', strtotime($endDate));
        }
        
        // Кнопки
        $calendar .= "<a class=\"btn btn-default bdc_btn fa fa-angle-left\" href=\"#\" onclick=\"showWeek(" .
            $pMonth . ',' . $pYear . ',' . $pCurrentMondayDateTStamp . "); return false;\"></a>\n";
        $calendar .= "<a class=\"btn btn-default bdc_btn fa fa-angle-right\" href=\"#\" onclick=\"showWeek(" .
            $nMonth . ',' . $nYear . ',' . $nCurrentMondayDateTStamp . "); return false;\"></a>\n";
            
        // Дата в шапке календаря    
        $calendar .= "<h3 style=\"display: inline-block;margin: 20px\">" . $monthInHeader . ' ' . $yearInHeader . '</h3>';
       

        /* Заглавия в таблице */
        $headings = array(
            'Пн',
            'Вт',
            'Ср',
            'Чт',
            'Пт',
            'Сб',
            'Вс');
        // $calendar .= '<tr class="calendar-row calendar-row--mobile 213"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">',
        //         $headings) . '</td></tr>';
        /* необходимые переменные дней и недель... */
        $running_day = 1;
        
        
        
        
        $running_day = $running_day - 1;
        if ($running_day < 0)
            $running_day = 6;
        $days_in_month = @date('t', @mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();
        /* первая строка календаря */
        $calendar .= '<tr class="calendar-row">';
        /* вывод пустых ячеек в сетке календаря */
        for ($x = 0; $x < $running_day; $x++):
            $calendar .= '<td class="calendar-day _irrelevantday _presentday"> </td>';
            $days_in_this_week++;
        endfor;
        $toDay = @date('d.m.Y');
        /* дошли до чисел, будем их писать в первую строку */
        while ($counterDays <= $endDate) {
            $curDate = date('d.m', strtotime($counterDays));
            $curDateFull = date('d.m.Y', strtotime($counterDays));
            $calendar .= '<td class="calendar-day ' . ($running_day == 5 || $running_day == 6 ? ' _dayoff' : '') . ($toDay == $curDateFull ? " _presentday" : "") . '">';
            $calendar .= "<div class=\"calendar-day__inner\">\n";
            $calendar .= "<div class=\"day-head\">\n";
            $calendar .= "<div class=\"day-number\">" . date('d.m', strtotime($counterDays)) . "</div>\n";
            $calendar .= "<div class=\"day-week\">" . $dow[$running_day] . "</div>\n";
            $calendar .= "</div>\n";
            $calendar .= "<div class=\"day-body\">\n";
            $counterDays = date('Y-m-d', strtotime($counterDays . ' +1 day'));
            if (isset($events[$curDateFull])) {
                // $ids = array();
                // foreach ($birthdays[$curDate] as $people) {
                //     $calendar .= "<a class=\"day-body__item\" href=\"javascript:;\" onclick=\"getEmployee(" . $people['id'] . ");\" data-bs-toggle=\"modal\" data-bs-target=\"#personModal\">\n";
                //     $calendar .= "<span class=\"day-event-icon icon-tort\"></span>\n";
                //     $calendar .= "<div class=\"day-event-name\">" . $people['text_data']['name'] . "</div>\n";
                //     $calendar .= "</a>\n";
                // }
                // $eIds = array();
                
                usort($events[$curDateFull], function($a, $b) {
                    return strcmp($a['dtmpl_data']['fields']['timeStart']['field_value'], $b['dtmpl_data']['fields']['timeStart']['field_value']);
                });
                
                foreach ($events[$curDateFull] as $event) {
                    $calendar .= "<a class=\"day-body__item\" href=\"javascript:;\" onclick=\"getEvent(" . $event['id'] . ");\" data-bs-toggle=\"modal\" data-bs-target=\"#lkEventModal\">\n";
                    $calendar .= "<span class=\"day-event-icon icon-holliday\"></span>\n";
                    $calendar .= "<div class=\"day-event-name\">" . $event['dtmpl_data']['fields']['timeStart']['field_value'] . " " . $event['text_data']['header'] . "</div>\n";
                    $calendar .= "</a>\n";
                }

                // $calendar .= "<div class=\"day-body__icons\">\n";
                // if (isset($birthdays[$curDate]))
                //     $calendar .= "<span class=\"day-event-icon icon-tort\"></span>\n";
                // if (isset($events[$curDateFull]))
                //     $calendar .= "<span class=\"day-event-icon icon-holliday\"></span>\n";
                // $calendar .= "</div>\n";
            }


            $calendar .= "</div>\n";
            $calendar .= "</div>\n";


            $calendar .= '</td>';
            if ($running_day == 6):
                $calendar .= '</tr>';
                if (($day_counter + 1) != $days_in_month):
                    $calendar .= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
                $counterWeek ++;
            endif;
            $days_in_this_week++;
            $running_day++;
            $day_counter++;
        }

        /* Выводим пустые ячейки в конце последней недели */
        if ($days_in_this_week > 1 && $days_in_this_week < 8):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar .= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;
        /* Закрываем последнюю строку */
        $calendar .= '</tr>';
        /* Закрываем таблицу */
        $calendar .= '</table>';

        /* Все сделано, возвращаем результат */
        return $calendar;
    }
}


if (!isset($month))
    $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : @date('n');
if (!isset($year))
    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : @date('Y');
if (!isset($currentMondayDate))
    $currentMondayDate = isset($_REQUEST['currentMondayDate']) ? $_REQUEST['currentMondayDate'] : @strtotime(@date('Y-m-d', strtotime('monday this week')));
$tStamp = @strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01'); // Временная метка в формате 1682888400 - 1 мая 2023 года 00:00

// print_r($_REQUEST);

$reallyDate = date('Y-m-d H:i:s', $currentMondayDate);

$start = $reallyDate; // Объявляем начало текущего месяца
$end = @date('Y-m-d', strtotime($reallyDate . ' +6 days')); // Объявляем конец текущего месяца

// $query = "select distinct mt.id from csct_lib_content mt left join csct_tdata_fields df1 on df1.data_id=mt.id where mt.status=1 and mt.ref_id in (1) and (((df1.field_id='158' and (DATE_FORMAT(df1.fdvalue, \"%m-%d\") >='" . @date('m-01', $tStamp) . "' and DATE_FORMAT(df1.fdvalue, \"%m-%d\") <='" . @date('m-t', $tStamp) . "'))))";
// $idsBd = $this->dbh->query($query)->fetchAll(PDO::FETCH_COLUMN); // Собираем айдишники всех именинников месяца

$query = "select distinct mt.id from csct_list_items mt left join csct_list_items_text nt on mt.id=nt.data_id left join csct_tdata_fields df1 on df1.data_id=mt.id where mt.status=1 and mt.parent_id in (126) and (((df1.field_id='160' and (DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") >='" . $start . "' and DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") <='" . $end . "')))) group by mt.id order by mt.num asc";
$idsEvents = $this->dbh->query($query)->fetchAll(PDO::FETCH_COLUMN);

$query1 = "select * from csct_list_items mt left join csct_list_items_text nt on mt.id=nt.data_id left join csct_tdata_fields df1 on df1.data_id=mt.id where mt.status=1 and mt.parent_id in (126) and (((df1.field_id='160' and (DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") >='" . $start . "' and DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") <='" . $end . "')))) group by mt.id order by mt.num asc";
$idsEventsFull = $this->dbh->query($query1)->fetchAll(PDO::FETCH_COLUMN);
echo"<pre>";
// print_r($idsEvents);
// print_r($idsEventsFull);
echo"</pre>";

$api = new api();
$api->set_type('list_items');
$api->set_vars('parent_id', 126);
if ($idsEvents) {
    $api->set_vars('id', join(',', $idsEvents));
    $dataEvent = $api->get_data();
    
    $events = array();
    foreach ($dataEvent as $itemEvent) {
        list($d, $m, $y) = explode('.', $itemEvent['dtmpl_data']['fields']['dayEvent']['field_value']);
        $date = $d . '.' . $m . '.' . $y;
        $events[$date][] = $itemEvent;
        
        
    }
}

/* Функция генерации календаря */
if (!function_exists('draw_Calendar')) {
    function draw_Calendar($month, $year, $events, $currentMondayDate)
    {
        /* Начало таблицы */
        $months = array(
            '',
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь');
        $dow = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье');
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar table js-calendar calendar-default">';
        $nMonth = $month + 1;
        $nYear = $year;
        $pYear = $year;
        if ($nMonth == 13) {
            $nMonth = 1;
            $nYear++;
        }
        $pMonth = $month - 1;
        if ($pMonth == 0) {
            $pMonth = 12;
            $pYear--;
        }
        // echo($currentMondayDate."<br>");
        $currentMondayDate = date('Y-m-d', $currentMondayDate);
        // echo($currentMondayDate."<br>");
        
        $pCurrentMondayDate = @date('Y-m-d', strtotime($currentMondayDate . ' -21 days'));
        // echo($pCurrentMondayDate." - ");
        $pCurrentMondayDateTStamp = @strtotime($pCurrentMondayDate);
        // echo($pCurrentMondayDateTStamp."<br>");
        
        $nCurrentMondayDate = @date('Y-m-d', strtotime($currentMondayDate . ' +21 days'));
        // echo($nCurrentMondayDate." - ");
        $nCurrentMondayDateTStamp = @strtotime($nCurrentMondayDate);
        // echo($nCurrentMondayDateTStamp."<br>");
        
        
        $currentMonday = $currentMondayDate;
        // echo("currentMonday - " . $currentMonday."<br>");
        $endDate = @date('Y-m-d', strtotime($currentMonday . ' +20 days'));
        $counterDays = $currentMonday;
        
        if (date('n', strtotime($currentMonday)) == date('n', strtotime($endDate))){
            $monthInHeader = $months[date('n', strtotime($currentMonday))];
        } else {
            $monthInHeader = $months[date('n', strtotime($currentMonday))] . ' - ' . $months[date('n', strtotime($endDate))];
        }
        
        if (date('Y', strtotime($currentMonday)) == date('Y', strtotime($endDate))){
            $yearInHeader = date('Y', strtotime($currentMonday));
        } else {
            $yearInHeader = date('Y', strtotime($currentMonday)) . '/' . date('Y', strtotime($endDate));
        }
        
        // Кнопки
        $calendar .= "<a class=\"btn btn-default bdc_btn fa fa-angle-left\" href=\"#\" onclick=\"showMonth(" .
            $pMonth . ',' . $pYear . ',' . $pCurrentMondayDateTStamp . "); return false;\"></a>\n";
        $calendar .= "<a class=\"btn btn-default bdc_btn fa fa-angle-right\" href=\"#\" onclick=\"showMonth(" .
            $nMonth . ',' . $nYear . ',' . $nCurrentMondayDateTStamp . "); return false;\"></a>\n";
            
        // Дата в шапке календаря    
        $calendar .= "<h3 style=\"display: inline-block;margin: 20px\">" . $monthInHeader . ' ' . $yearInHeader . '</h3>';
       

        /* Заглавия в таблице */
        $headings = array(
            'Пн',
            'Вт',
            'Ср',
            'Чт',
            'Пт',
            'Сб',
            'Вс');
        // $calendar .= '<tr class="calendar-row calendar-row--mobile 213"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">',
        //         $headings) . '</td></tr>';
        /* необходимые переменные дней и недель... */
        $running_day = 1;
        
        
        
        
        $running_day = $running_day - 1;
        if ($running_day < 0)
            $running_day = 6;
        $days_in_month = @date('t', @mktime(0, 0, 0, $month, 1, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();
        /* первая строка календаря */
        $calendar .= '<tr class="calendar-row">';
        /* вывод пустых ячеек в сетке календаря */
        for ($x = 0; $x < $running_day; $x++):
            $calendar .= '<td class="calendar-day _irrelevantday _presentday"> </td>';
            $days_in_this_week++;
        endfor;
        $toDay = @date('d.m.Y');
        /* дошли до чисел, будем их писать в первую строку */
        while ($counterDays <= $endDate) {
            $curDate = date('d.m', strtotime($counterDays));
            $curDateFull = date('d.m.Y', strtotime($counterDays));
            $calendar .= '<td class="calendar-day ' . ($running_day == 5 || $running_day == 6 ? ' _dayoff' : '') . ($toDay == $curDateFull ? " _presentday" : "") . '">';
            $calendar .= "<div class=\"calendar-day__inner\">\n";
            $calendar .= "<div class=\"day-head\">\n";
            $calendar .= "<div class=\"day-number\">" . date('d.m', strtotime($counterDays)) . "</div>\n";
            $calendar .= "<div class=\"day-week\">" . $dow[$running_day] . "</div>\n";
            $calendar .= "</div>\n";
            $calendar .= "<div class=\"day-body\">\n";
            $counterDays = date('Y-m-d', strtotime($counterDays . ' +1 day'));
            if (isset($events[$curDateFull])) {
                // $ids = array();
                // foreach ($birthdays[$curDate] as $people) {
                //     $calendar .= "<a class=\"day-body__item\" href=\"javascript:;\" onclick=\"getEmployee(" . $people['id'] . ");\" data-bs-toggle=\"modal\" data-bs-target=\"#personModal\">\n";
                //     $calendar .= "<span class=\"day-event-icon icon-tort\"></span>\n";
                //     $calendar .= "<div class=\"day-event-name\">" . $people['text_data']['name'] . "</div>\n";
                //     $calendar .= "</a>\n";
                // }
                // $eIds = array();
                
                
                usort($events[$curDateFull], function($a, $b) {
                    return strcmp($a['dtmpl_data']['fields']['timeStart']['field_value'], $b['dtmpl_data']['fields']['timeStart']['field_value']);
                });
                
                
                foreach ($events[$curDateFull] as $event) {
                    $calendar .= "<a class=\"day-body__item\" href=\"javascript:;\" onclick=\"getEvent(" . $event['id'] . ");\" data-bs-toggle=\"modal\" data-bs-target=\"#lkEventModal\">\n";
                    $calendar .= "<span class=\"day-event-icon icon-holliday\"></span>\n";
                    $calendar .= "<div class=\"day-event-name\">" . $event['dtmpl_data']['fields']['timeStart']['field_value'] . " " . $event['text_data']['header'] . "</div>\n";
                    $calendar .= "</a>\n";
                }

                // $calendar .= "<div class=\"day-body__icons\">\n";
                // if (isset($birthdays[$curDate]))
                //     $calendar .= "<span class=\"day-event-icon icon-tort\"></span>\n";
                // if (isset($events[$curDateFull]))
                //     $calendar .= "<span class=\"day-event-icon icon-holliday\"></span>\n";
                // $calendar .= "</div>\n";
            }


            $calendar .= "</div>\n";
            $calendar .= "</div>\n";


            $calendar .= '</td>';
            if ($running_day == 6):
                $calendar .= '</tr>';
                if (($day_counter + 1) != $days_in_month):
                    $calendar .= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
                $counterWeek ++;
            endif;
            $days_in_this_week++;
            $running_day++;
            $day_counter++;
        }

        /* Выводим пустые ячейки в конце последней недели */
        if ($days_in_this_week > 1 && $days_in_this_week < 8):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar .= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;
        /* Закрываем последнюю строку */
        $calendar .= '</tr>';
        /* Закрываем таблицу */
        $calendar .= '</table>';

        /* Все сделано, возвращаем результат */
        return $calendar;
    }
}


if (!isset($month))
    $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : @date('n');
if (!isset($year))
    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : @date('Y');
if (!isset($currentMondayDate))
    $currentMondayDate = isset($_REQUEST['currentMondayDate']) ? $_REQUEST['currentMondayDate'] : @strtotime(@date('Y-m-d', strtotime('monday this week')));
$tStamp = @strtotime($year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01'); // Временная метка в формате 1682888400 - 1 мая 2023 года 00:00

// print_r($_REQUEST);

$reallyDate = date('Y-m-d H:i:s', $currentMondayDate);

$start = $reallyDate; // Объявляем начало текущего месяца
$end = @date('Y-m-d', strtotime($reallyDate . ' +20 days')); // Объявляем конец текущего месяца

// $query = "select distinct mt.id from csct_lib_content mt left join csct_tdata_fields df1 on df1.data_id=mt.id where mt.status=1 and mt.ref_id in (1) and (((df1.field_id='158' and (DATE_FORMAT(df1.fdvalue, \"%m-%d\") >='" . @date('m-01', $tStamp) . "' and DATE_FORMAT(df1.fdvalue, \"%m-%d\") <='" . @date('m-t', $tStamp) . "'))))";
// $idsBd = $this->dbh->query($query)->fetchAll(PDO::FETCH_COLUMN); // Собираем айдишники всех именинников месяца

$query = "select distinct mt.id from csct_list_items mt left join csct_list_items_text nt on mt.id=nt.data_id left join csct_tdata_fields df1 on df1.data_id=mt.id where mt.status=1 and mt.parent_id in (126) and (((df1.field_id='160' and (DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") >='" . $start . "' and DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") <='" . $end . "')))) group by mt.id order by mt.num asc";
$idsEvents = $this->dbh->query($query)->fetchAll(PDO::FETCH_COLUMN);

$query1 = "select * from csct_list_items mt left join csct_list_items_text nt on mt.id=nt.data_id left join csct_tdata_fields df1 on df1.data_id=mt.id where mt.status=1 and mt.parent_id in (126) and (((df1.field_id='160' and (DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") >='" . $start . "' and DATE_FORMAT(df1.fdvalue, \"%Y-%m-%d\") <='" . $end . "')))) group by mt.id order by mt.num asc";
$idsEventsFull = $this->dbh->query($query1)->fetchAll(PDO::FETCH_COLUMN);
echo"<pre>";
// print_r($idsEvents);
// print_r($idsEventsFull);
echo"</pre>";

$api = new api();
$api->set_type('list_items');
$api->set_vars('parent_id', 126);
if ($idsEvents) {
    $api->set_vars('id', join(',', $idsEvents));
    $dataEvent = $api->get_data();
    
    $events = array();
    foreach ($dataEvent as $itemEvent) {
        list($d, $m, $y) = explode('.', $itemEvent['dtmpl_data']['fields']['dayEvent']['field_value']);
        $date = $d . '.' . $m . '.' . $y;
        $events[$date][] = $itemEvent;
        
        
    }
    
}

?>
<div class="container">
    <pre style="display: none;">
        <?php 
            print_r($events); 
        ?>
    </pre>
    <?php echo draw_Weekcalendar($month, $year, $events, $currentMondayDate); ?>
    <?php echo draw_Calendar($month, $year, $events, $currentMondayDate); ?>
</div>

<script>
    function showWeek(m, y, d) {
        $.post('/shbcalweek/', {'month': m, 'year': y, 'currentMondayDate': d}, function (data) {
            $('#bdc_contentWeek').html(data);
        });
        // console.log(d);
    }
</script>
<script>
    function showMonth(m, y, d) {
        $.post('/shbcal/', {'month': m, 'year': y, 'currentMondayDate': d}, function (data) {
            $('#bdc_content').html(data);
        });
        // console.log(d);
    }
</script>

?>
