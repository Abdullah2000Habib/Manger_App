<?php
use Aws\CloudWatch\CloudWatchClient;
use Aws\Exception\AwsException;
 function diplayNumberOfInstances($numberOfInstances){
     return $numberOfInstances * 12.5;
 }
function show($data){
     echo "<pre>";
     print_r($data);
     echo "</pre>";
}
function listMetrics($cloudWatchClient)
{
    try {
        $result = $cloudWatchClient->listMetrics();
        if (isset($result['@metadata']['effectiveUri'])) {
            if ((isset($result['Metrics'])) and
                (count($result['Metrics']) > 0)) {
                foreach ($result['Metrics'] as $metric) {
                    show($metric);
                    /*if ($metric["Namespace"] == "MyNamespace" && $metric["MetricName"] == "mem-cache") {
                        if ((isset($metric['Dimensions'])) and
                            (count($metric['Dimensions']) > 0)) {
                            return $metric["Dimensions"];
                        }
                    }*/
                }
            }
        }
        return false;
    } catch (AwsException $e) {
        return 'Error: ' . $e->getAwsErrorMessage();
    }
}
function getMetricStatistics($cloudWatchClient, $namespace, $metricName,
                             $dimensions, $startTime, $endTime, $period, $statistics, $unit)
{
    try {
        $result = $cloudWatchClient->getMetricStatistics([
            'Namespace' => $namespace,
            'MetricName' => $metricName,
            'Dimensions' => $dimensions,
            'StartTime' => $startTime,
            'EndTime' => $endTime,
            'Period' => $period,
            'Statistics' => $statistics,
            'Unit' => $unit
        ]);
        show($result);

        $message = '';

        if (isset($result['@metadata']['effectiveUri']))
        {
            $message .= 'For the effective URI at ' .
                $result['@metadata']['effectiveUri'] . "\n\n";

            if ((isset($result['Datapoints'])) and
                (count($result['Datapoints']) > 0))
            {
                $message .= "Datapoints found:\n\n";

                foreach($result['Datapoints'] as $datapoint)
                {
                    foreach ($datapoint as $key => $value)
                    {
                        $message .= $key . ' = ' . $value . "\n";
                    }

                    $message .= "\n";
                }
            } else {
                $message .= 'No datapoints found.';
            }
        } else {
            $message .= 'No datapoints found.';
        }

        return $message;
    } catch (AwsException $e) {
        return 'Error: ' . $e->getAwsErrorMessage();
    }
}

