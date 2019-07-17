<?php
namespace core;

require 'vendor/autoload.php';

$data = $_GET;
$clanId = $data['clan-id'];
$tStart = 1561582800;

$html = '
    <html>
        <head>
        <!-- services/fightsStata/css/tables.css -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
            <link rel="stylesheet" href="services/wcAchivments/css/icons.css">
            <link rel="stylesheet" href="services/wcAchivments/css/normilize.css">
        </head>
        
        <body>
            <form method="get" action="ajax.php">
                <div class="form-group" style="width: 100px;">
                    <input type="text" id="clan-id" name="clan-id" placeholder="ID клана">
                </div>
        
                <button type="submit" class="btn btn-primary">Получить данные</button>
            </form>
            
        <p>Сервис не учитывает персонажей, которые провели <b>ВСЕ</b> бои под клановой аурой невидимости, а так же персонажей, которые вступили в заявку, но бой не начался...</p>
';

if ($clanId == 'Оберон') {
    $html .= '
        <iframe width="560" height="315" src="https://www.youtube.com/embed/aGob2BwZvmI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    ';
}

if ($clanId && is_numeric($clanId)) {
    $logs = new Logs();
    $logsData = $logs->getLogsByClanId($clanId);

    if (count($logsData)) {
        $html .= '
            <table class="table table-striped table-bordered" style="width: 125px; text-align: center"><thead class="thead-inverse"><tr><td>Время старта боев</td></tr></thead> 
        ';

        foreach ($logsData as $time) {
            $html .= '
            <tr><td>'.$time.'</td></tr> 
        ';
        }

        $html .= '</table><br /> <hr style="width: 90%;" <br />';
    }

    $participants = new Participants();
    $participantsData = $participants->getRecordsByClanId($clanId);

    if (count($participantsData)) {
        $html .= '
            <div style="width: 850px">
            <table class="table table-striped table-bordered" style="text-align: center">
                <thead class="thead-inverse">
                    <tr>
                        <td><b>Персонаж</b></td>
                        <td><b>Получит ачивку ЧМ</b></td>
                        <td><b>Участие в бою</b></td>
                        <td><b>Благословление</b></td>
                        <td><b>Был в игре со старта турнира</b></td>
                    </tr>
                </thead> 
        ';

        foreach ($participantsData as $playerData) {
            $html .= '
                <tr>
                    <td> <a href="https://www.ereality.ru/~'.$playerData['name'].'" target="_blank">'.$playerData['name'].'</a></td>
                    <td> <span class="'.($playerData['part'] || $playerData['bless'] ? 'icon-large icon-ok' : 'icon-large icon-remove').'"></span></td>
                    <td> <span class="'.($playerData['part'] ? 'icon-large icon-ok' : 'icon-large icon-remove').'"></span></td>
                    <td> <span class="'.($playerData['bless'] ? 'icon-large icon-ok' : 'icon-large icon-remove').'"></span></td>
                    <td> <span class="'.($playerData['last_visit'] > $tStart ? 'icon-large icon-ok' : 'icon-large icon-remove').'"></span></td>
                </tr>
            ';
        }

        $html .= '</table></div>';
    }
}

$html .= '</body></html>';

echo $html;