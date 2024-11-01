<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class srs_graph{
    public static function showGraph(){
        $graph_values = array();
        $reservations = srs_reservations::$reservation_graph;
//        print_r($reservations);

        for($hours=0;$hours<=23;$hours++){
            $x=0;
            $mins = 0;
            if($hours<10){
                $hours = "0$hours";
            }
            while ($mins<=45){
//                echo "$hours:$mins:00/";
                if($mins<10){
                    $mins = "0$mins";
                }
                // Pending Graph
                foreach($reservations['pending'] as $reservation){
//                    echo "x".$x++."\n";
//                    echo $reservation['res_time']." and " . $reservation['total']."\n";
                    if( $reservation['res_time'] == "$hours:$mins:00" ){
                        $graph_values_pending["$hours:$mins:00"] = $reservation['total'];
                        break;
                    }else{
                        $graph_values_pending["$hours:$mins:00"] = 0;
                    }
                }
                // Confirmed Graph
                foreach($reservations['confirmed'] as $reservation){
//                    echo "x".$x++."\n";
//                    echo $reservation['res_time']." and " . $reservation['total']."\n";
                    if( $reservation['res_time'] == "$hours:$mins:00" ){
                        $graph_values_confirmed["$hours:$mins:00"] = $reservation['total'];
                        break;
                    }else{
                        $graph_values_confirmed["$hours:$mins:00"] = 0;
                    }
                }
                $mins+=15 ;
            }
        }
//        print_r($graph_values_confirmed);
        ?>
        <script type="text/javascript">

            jQuery(function () {

                var newh = jQuery(".srs-graph").height();

                jQuery( window ).resize(function() {

                    newh = jQuery(".srs-graph").height();
                    chart1.redraw();
                    chart1.reflow();
                });

                chart1 = new Highcharts.Chart({
                    chart: {
                        renderTo: 'srs-graph-container',
                        type: 'line',
                        marginRight: 10,
                        marginBottom: 30,
                        height: 140,
                        plotBorderColor: '#cccccc',
                        plotBorderWidth: 2,
                        borderColor: '#eeeeee',
                        borderRadius: 0,
                        backgroundColor: '',
                        borderWidth: 2
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: ''
                    },

                    legend: {
                        enabled: false,

                    },
                    xAxis: {
                        tickLength: 0,
                        categories: [<?php
                           echo "
                              '0', '','','',
                              '1', '','','',
                              '2', '','','',
                              '3', '','','',
                              '4', '','','',
                              '5', '','','',
                              '6', '','','',
                              '7', '','','',
                              '8', '','','',
                              '9', '','','',
                              '10', '','','',
                              '11', '','','',
                              '12', '','','',
                              '13', '','','',
                              '14', '','','',
                              '15', '','','',
                              '16', '','','',
                              '17', '','','',
                              '18', '','','',
                              '19', '','','',
                              '20', '','','',
                              '21', '','','',
                              '22', '','','',
                              '23', '','','',
                              
                           ";
//                         $x=0;
//                         $y=1;
//                         foreach($graph_values_pending as $key=>$value){
//                             if($x==0||$y>=4){
//                                 echo "'".$x."',";
//                                 $x++;
//                                 $y=0;
//                             }else{
//                                 echo "'',";
//                             }
//                             $y++;
//                         }
                        ?>],
                        gridLineColor: '#cccccc',
                        gridLineWidth: 0,
                        tickmarkPlacement: 'between',

                        labels: {
                            formatter: function () {
                                return '<span style="fill: #0793D1;font-size:9px;">' + this.value + '</span>';
                            }
                        }

                    },
                    yAxis: {
                        tickInterval: 10,
                        /*max: 200,
                        min: -5,*/
                        startOnTick: false,
                        title: {
                            enabled: false
                        },
                        gridLineColor: '#cccccc',
                        gridLineWidth: 0.5,
                        labels: {
                            formatter: function () {
                                return '<span style="fill: #0793D1;font-size:9px;">' + this.value + '</span>';
                            }
                        }/*,
                        offset:-5*/
                    },
                    series: [{
                        name: 'Confirmed',
                        data: [<?php if($graph_values_confirmed){foreach($graph_values_confirmed as $key=>$value){echo $value.",";}}else{echo '';} ?>],
                        lineWidth: 1

                    },{
                        name: 'Pending',
                        data: [<?php if($graph_values_pending){foreach($graph_values_pending as $key=>$value){echo $value.",";}}else{echo '';} ?>],
                        lineWidth: 1

                    }],
                    plotOptions: {
                         series: {
                             marker: {
                                 enabled: false
                             }
                         }/*,
                        column: {
                            pointPadding: -10,
                            borderWidth: 0
                        }*/
                    },
                    tooltip: {
                        enabled: false
                    },

                    exporting: {
                        enabled: false
                    },
                    colors: [
                        '#0793D1',
                        '#FF6600',
                        '#8bbc21',
                        '#910000',
                        '#1aadce',
                        '#492970',
                        '#f28f43',
                        '#77a1e5',
                        '#c42525',
                        '#a6c96a'
                    ]

                },function(chart){

                    /*chart.xAxis[0].labelGroup.translate(10,0);*/

                });

            });
        </script>
    <?php
    }
}
?>