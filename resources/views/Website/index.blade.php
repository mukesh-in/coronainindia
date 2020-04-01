
<?php
$url = url('/');
?>
@extends('Layout.web')
@section('page-content')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<section class="background-pattern">
<section class="about-sec py-5">
  	<div class="container">
  		<div class="body">
            <div class="row" id="statewiseline">
            </div>
            <div class="col-sm-7">
            <div class="table-responsive">
                <table class="table table-bordered" id="statewise">
                    <thead>
                        <tr>
                            <th>State/UT</th>
                            <th>Confirmed</th>
                            <th>Death</th>
                            <th>Recovered</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $key => $value)
                        <tr>
                            <td><a href="javascript:void()" data-toggle="modal" data-target="#<?php echo str_replace(' ','',$value->state) ?>"><span class="glyphicon glyphicon-triangle-right"></span>{{ $value->state }}</a>
                                @if($value->state != 'Total' && $value->confirmed > 0)
                                <?php $stateName = $value->state; ?>
                                <div class="modal fade" id="<?php echo str_replace(' ','',$stateName) ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body text-center">
                                            <table  class="table table-bordered">
                                                <tr>
                                                    <th colspan="2" class="text-center text-success">
                                                        District(s) Of {{$stateName}}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>District</th>
                                                    <th>Confirmed</th>
                                                </tr>
                                                @foreach($state->$stateName->districtData as $disKey => $district)
                                                <tr>
                                                    <td>{{ ($disKey == 'Unknown')?'Un Identified':$disKey }}</td>
                                                    <td>{{ $district->confirmed }}<?php echo ($district->delta->confirmed)?"<br><span class='text-danger text-small'>+".$district->delta->confirmed."</span>":'' ?></td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td>{{ $value->confirmed }}<?php echo ($value->delta->confirmed)?"<br><span class='text-danger text-small'>+".$value->delta->confirmed."</span>":'' ?></td>
                            <td>{{ $value->deaths }}<?php echo ($value->delta->deaths)?"<br><span class='text-danger text-small'>+".$value->delta->deaths."</span>":'' ?></td>
                            <td>{{ $value->recovered }}<?php echo ($value->delta->recovered)?"<br><span class='text-danger text-small'>+".$value->delta->recovered."</span>":'' ?></td>
                            <td>{{ $value->active }}<?php echo ($value->delta->active)?"<br><span class='text-danger text-small'>+".$value->delta->active."</span>":'' ?></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="table-responsive">
                <table class="table table-bordered" id="datewise">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Confirmed</th>
                            <th>Recovered</th>
                            <th>Death</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dateWise as $key => $value)
                        <tr>
                            <td>{{ $value->date }}</td>
                            <td>{{ $value->dailyconfirmed }}</td>
                            <td>{{ $value->dailyrecovered }}</td>
                            <td>{{ $value->dailydeceased }}</td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>  
	</div>
</section>
</section>
<script type="text/javascript">
    $(document).ready( function () {
    $('#datewise').DataTable({
        paging: false,
         "order": [[ 1, "desc" ]],
         "bInfo" : false
    }); 
    $('#statewise').DataTable({
        paging: false,
        ordering:false,
        "bInfo" : false
    });     
} );

    Highcharts.chart('statewiseline', {
    chart: {
        height: 600,
        type: 'line'
    },
    title: {
        text: 'State Wise Covid-19 Status'
    },
    xAxis: {
        categories:<?php echo $statename ?>
    },
    yAxis: {
        title: {
            text: 'No. Of Cases'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    exporting: { enabled: false },

    credits: {
        enabled: false
    },
    series:<?php echo $statewise ?>
});

</script>
@endsection